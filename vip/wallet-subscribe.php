<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate plan ID
if (!isset($_POST['plan_id']) || !is_numeric($_POST['plan_id'])) {
    $_SESSION['error'] = "Invalid plan selection.";
    header("Location: buy-plan");
    exit;
}

$plan_id = (int) $_POST['plan_id'];

// Fetch plan details
$stmt_plan = $dbh->prepare("SELECT * FROM plans WHERE id = :pid LIMIT 1");
$stmt_plan->execute([':pid' => $plan_id]);
$plan = $stmt_plan->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    $_SESSION['error'] = "Plan not found.";
    header("Location: buy-plan");
    exit;
}

// Fetch user details
$stmt_user = $dbh->prepare("SELECT full_name, email, wallet_balance FROM users WHERE id = :uid LIMIT 1");
$stmt_user->execute([':uid' => $user_id]);
$row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$row_user) {
    $_SESSION['error'] = "User not found.";
    header("Location: buy-plan");
    exit;
}

$first_name      = $row_user['full_name'] ?? '';
$current_balance = $row_user['wallet_balance'] ?? 0;

// Check balance
if ($current_balance < $plan['amount']) {
    $_SESSION['error'] = "Insufficient wallet balance.";
    header("Location: buy-plan");
    exit;
}

// Deduct balance
$new_balance = $current_balance - $plan['amount'];
$update_balance = $dbh->prepare("UPDATE users SET wallet_balance = :bal WHERE id = :uid");
$update_balance->execute([':bal' => $new_balance, ':uid' => $user_id]);

// Create subscription
$stmt_sub = $dbh->prepare("INSERT INTO subscriptions (user_id, plan_id, start_date, end_date) 
                           VALUES (:uid, :pid, NOW(), DATE_ADD(NOW(), INTERVAL :duration DAY))");
$stmt_sub->execute([
    ':uid'      => $user_id,
    ':pid'      => $plan_id,
    ':duration' => $plan['duration']
]);

// Calculate dates for email
$date_time  = date('Y-m-d H:i:s'); // Date of payment
$expiry_date = date('Y-m-d H:i:s', strtotime("+{$plan['duration']} days"));

// Log transaction
$transaction_id = uniqid();
$stmt_tx = $dbh->prepare("INSERT INTO payments (reference_id, user_id, amount, payment_method, status) 
                          VALUES (:ref_id, :uid, :amt, 'wallet', 'success')");
$stmt_tx->execute([
    ':ref_id' => $transaction_id,
    ':uid'    => $user_id,
    ':amt'    => $plan['amount']
]);

// Email variables
$amount          = $plan['amount'];
$wallet_balance  = number_format($new_balance, 2);
$support_email   = $support_email ?? 'support@example.com';
$app_url         = $app_url ?? 'https://example.com/';
$app_name        = $app_name ?? 'MyApp';

// Email subject
$subject = "Subscription Purchase Successful";

// Email HTML
$message = '
<html>
<head>
  <meta charset="UTF-8">
  <title>Subscription Purchase Successful</title>
  <style>
    body { font-family: "Segoe UI", sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
    .container { max-width: 620px; margin: 40px auto; background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.05); }
    h2 { color: #2d8cf0; margin-top: 0; }
    p { color: #333; font-size: 15px; line-height: 1.6; }
    .details { background: #f7f9fc; padding: 15px; border-radius: 6px; margin: 15px 0; }
    .details table { width: 100%; border-collapse: collapse; }
    .details td { padding: 6px 0; vertical-align: top; }
    .label { color: #555; width: 45%; font-weight: 500; }
    .value { color: #111; font-weight: 600; }
    .highlight { background: #e8f4ff; padding: 10px; border-radius: 6px; font-size: 16px; margin-bottom: 10px; }
    .btn { display: inline-block; margin-top: 20px; background: #2d8cf0; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
    .footer { margin-top: 30px; text-align: center; font-size: 13px; color: #999; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Subscription Purchase Successful</h2>
    <p>Hello <strong>' . htmlspecialchars($first_name) . '</strong>,</p>
    <p>Your subscription to the <strong>' . htmlspecialchars($plan['name']) . '</strong> plan was successful.</p>

    <div class="highlight">
      📅 <strong>Plan Subscribed For:</strong> ' . htmlspecialchars($plan['duration']) . ' Days
    </div>

    <div class="details">
      <table>
        <tr>
          <td class="label">Amount Deducted</td>
          <td class="value">₦' . number_format($amount, 2) . '</td>
        </tr>
        <tr>
          <td class="label">Payment Method</td>
          <td class="value">Wallet</td>
        </tr>
        <tr>
          <td class="label">Transaction ID</td>
          <td class="value">' . htmlspecialchars($transaction_id) . '</td>
        </tr>
        <tr>
          <td class="label">Date of Payment</td>
          <td class="value">' . htmlspecialchars($date_time) . '</td>
        </tr>
        <tr>
          <td class="label">Plan Duration</td>
          <td class="value">' . htmlspecialchars($plan['duration']) . ' Days</td>
        </tr>
        <tr>
          <td class="label">Expiry Date</td>
          <td class="value">' . htmlspecialchars($expiry_date) . '</td>
        </tr>
        <tr>
          <td class="label">Updated Wallet Balance</td>
          <td class="value">₦' . $wallet_balance . '</td>
        </tr>
      </table>
    </div>

    <p>You can now enjoy your VIP plan benefits immediately.</p>
    <p>If you have any questions or notice any discrepancies, please contact our support team at <a href="mailto:' . htmlspecialchars($support_email) . '">' . htmlspecialchars($support_email) . '</a> or via live chat.</p>

    <p>
      <a href="' . htmlspecialchars($app_url) . 'vip" class="btn">Go to Dashboard</a>
    </p>

    <p class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name) . '. All rights reserved.</p>
  </div>
</body>
</html>
';

// Send email
sendEmail($row_user['email'], $subject, $message);

// Redirect
$_SESSION['toast'] = [
    'type'    => 'success',
    'message' => 'Subscription was successful!'
];
header("Location: index");
exit;
?>
