<?php
// verify-plan.php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$reference = isset($_GET['reference']) ? trim($_GET['reference']) : '';
$plan_id   = isset($_GET['plan_id'])   ? intval($_GET['plan_id']) : 0;

if (empty($reference) || empty($plan_id)) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid payment verification request.'];
    header("Location: buy-plan");
    exit;
}

// Basic fallback vars (in case they aren't provided by your includes)
$current_date = isset($current_date) ? $current_date : date('Y-m-d H:i:s');
$ip_address = isset($ip_address) ? $ip_address : ($_SERVER['REMOTE_ADDR'] ?? '');

// Fetch plan details
$stmt = $dbh->prepare("SELECT * FROM plans WHERE id = ?");
$stmt->execute([$plan_id]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Selected plan not found.'];
    header("Location: buy-plan");
    exit;
}

// Verify payment with Paystack
$paystack_secret_key = $row_website['paystack_secret_key'] ?? '';
if (empty($paystack_secret_key)) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Payment gateway not configured.'];
    header("Location: buy-plan");
    exit;
}

$verify_url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verify_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $paystack_secret_key,
    "Content-Type: application/json",
]);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_error($ch);
curl_close($ch);

if ($response === false) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Could not contact payment gateway: ' . $curl_err];
    header("Location: buy-plan");
    exit;
}

$payload = json_decode($response, true);
if (!is_array($payload) || !isset($payload['data']['status'])) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid response from payment gateway.'];
    header("Location: buy-plan");
    exit;
}

// Ensure transaction was successful
$status = $payload['data']['status'];
$paid_amount_kobo = isset($payload['data']['amount']) ? intval($payload['data']['amount']) : 0; // kobo
$paid_currency = $payload['data']['currency'] ?? 'NGN';

if ($status !== 'success') {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Payment not successful.'];
    header("Location: buy-plan");
    exit;
}

// Optional: verify amount matches plan amount (defensive)
$expected_kobo = intval(round(floatval($plan['amount']) * 100));
if ($paid_amount_kobo < $expected_kobo) {
    // suspicious: paid less than expected
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Payment amount does not match the selected plan. Contact support.'];
    header("Location: buy-plan");
    exit;
}

// Compute subscription start and end dates
$start_date = date('Y-m-d');
$duration_days = intval($plan['duration']);
$end_date = date('Y-m-d', strtotime("+{$duration_days} days"));

// Insert subscription record
$insertSub = $dbh->prepare("INSERT INTO subscriptions (user_id, plan_id, start_date, end_date) VALUES (?, ?, ?, ?)");
$success = $insertSub->execute([$user_id, $plan_id, $start_date, $end_date]);

if (!$success) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to create subscription record. Contact support.'];
    header("Location: buy-plan");
    exit;
}

$subscription_id = $dbh->lastInsertId();


$action = "Subscribed to plan '{$plan['name']}' (Plan ID: {$plan['id']}, Subscription ID: {$subscription_id})";
 log_activity($dbh, $user_id, $action, 'subscriptions', $subscription_id, $ip_address);


// Send professional subscription email
$subject = "Subscription Confirmed — " . $plan['name'];

$message = '
<html>
<head>
  <meta charset="UTF-8">
  <title>Subscription Confirmation</title>
  <style>
    body { font-family: "Segoe UI", sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
    .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.05); }
    .header { text-align: center; margin-bottom: 20px; }
    .header h2 { color: #2d8cf0; margin: 0; }
    .content { font-size: 15px; color: #333; line-height: 1.6; }
    .summary { background: #f7f9fc; padding: 15px; border-radius: 6px; margin: 15px 0; }
    .footer { margin-top: 30px; text-align: center; font-size: 13px; color: #999; }
    .btn { display: inline-block; margin-top: 20px; background: #2d8cf0; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 6px 0; vertical-align: top; }
    .label { color: #555; width: 40%; }
    .value { color: #111; font-weight: 600; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Subscription Confirmed</h2>
    </div>
    <div class="content">
      <p>Hi <strong>' . htmlspecialchars($row_user['full_name'] ?? $row_user['email']) . '</strong>,</p>

      <p>Thank you for subscribing to our <strong>' . htmlspecialchars($plan['name']) . '</strong> plan. Your payment has been successfully received and your subscription is now active.</p>

      <div class="summary">
        <table>
          <tr>
            <td class="label">Plan</td>
            <td class="value">' . htmlspecialchars($plan['name']) . '</td>
          </tr>
          <tr>
            <td class="label">Amount</td>
            <td class="value">&#8358;' . number_format($plan['amount'], 2) . ' ' . htmlspecialchars($paid_currency) . '</td>
          </tr>
          <tr>
            <td class="label">Start Date</td>
            <td class="value">' . htmlspecialchars($start_date) . '</td>
          </tr>
          <tr>
            <td class="label">End Date</td>
            <td class="value">' . htmlspecialchars($end_date) . '</td>
          </tr>
          <tr>
            <td class="label">Subscription ID</td>
            <td class="value">' . htmlspecialchars($subscription_id) . '</td>
          </tr>
          <tr>
            <td class="label">Payment Reference</td>
            <td class="value">' . htmlspecialchars($reference) . '</td>
          </tr>
        </table>
      </div>

      <p>If you did not authorize this subscription, please contact our support team immediately.</p>

      <p>
        <a href="' . htmlspecialchars($app_url . 'vip') . '" class="btn">Go to Dashboard</a>
      </p>

      <p class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name) . '. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
';

$recipient = $row_user['email'] ?? null;
if ($recipient) {
    try {
        sendEmail($recipient, $subject, $message);
    } catch (\Throwable $e) {
        // best-effort; do not block
    }
}

// Redirect back to buy-plan (or VIP dashboard) with success message
$_SESSION['toast'] = ['type' => 'success', 'message' => 'Subscription successful! Your plan is now active.'];
header("Location: index");
exit;
