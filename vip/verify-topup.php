<?php
// verify-topup.php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$user_id   = intval($_SESSION['user_id']);
$reference = isset($_GET['reference']) ? trim($_GET['reference']) : '';
$amount    = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;

if (!$reference || $amount <= 0) {
    die("Invalid transaction parameters.");
}

// STEP 1 — VERIFY WITH PAYSTACK API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . urlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $paystack_secret_key
]);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (!$result || !isset($result['status']) || !$result['status']) {
    die("Error verifying transaction.");
}

if ($result['data']['status'] !== 'success') {
    die("Transaction not successful.");
}

$payment_method = ucfirst($result['data']['channel'] ?? 'Paystack');
$date_time      = date('j M Y, g:i A');
$transaction_id = $reference;

try {
    $dbh->beginTransaction();

    // Insert payment record
    $stmt = $dbh->prepare("
        INSERT INTO payments (reference_id, user_id, amount, payment_method, status)
        VALUES (:reference_id, :user_id, :amount, :method, 'success')
    ");
    $stmt->execute([
        ':user_id'      => $user_id,
        ':amount'       => $amount,
        ':reference_id' => $reference,
        ':method'       => $payment_method
    ]);

    // Update wallet balance
    $stmt = $dbh->prepare("UPDATE users SET wallet_balance = wallet_balance + :amount WHERE id = :user_id");
    $stmt->execute([
        ':amount'  => $amount,
        ':user_id' => $user_id
    ]);

     $dbh->commit();

    // Prepare styled email
    $first_name    = explode(' ', trim($row_user['full_name'] ?? ''))[0] ?: 'User';
    $wallet_balance = number_format($row_user['wallet_balance'], 2);
    $subject = "Wallet Top-up Successful";

    $message = '
    <html>
    <head>
      <meta charset="UTF-8">
      <title>Wallet Top-up Successful</title>
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
        .btn { display: inline-block; margin-top: 20px; background: #2d8cf0; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
        .footer { margin-top: 30px; text-align: center; font-size: 13px; color: #999; }
      </style>
    </head>
    <body>
      <div class="container">
        <h2>Wallet Top-up Successful</h2>
        <p>Hello <strong>' . htmlspecialchars($first_name) . '</strong>,</p>
        <p>We’re pleased to inform you that your wallet top-up was successful.</p>

        <div class="details">
          <table>
            <tr>
              <td class="label">Amount Credited</td>
              <td class="value">₦' . number_format($amount, 2) . '</td>
            </tr>
            <tr>
              <td class="label">Payment Method</td>
              <td class="value">' . htmlspecialchars($payment_method) . '</td>
            </tr>
            <tr>
              <td class="label">Transaction ID</td>
              <td class="value">' . htmlspecialchars($transaction_id) . '</td>
            </tr>
            <tr>
              <td class="label">Date &amp; Time</td>
              <td class="value">' . htmlspecialchars($date_time) . '</td>
            </tr>
            <tr>
              <td class="label">Updated Wallet Balance</td>
              <td class="value">₦' . $wallet_balance . '</td>
            </tr>
          </table>
        </div>

        <p>You can start using your wallet to purchase match predictions, VIP plans, or other premium features on our platform.</p>
        <p>If you have any questions or notice any discrepancies, please contact our support team at <a href="mailto:' . htmlspecialchars($support_email) . '">' . htmlspecialchars($support_email) . '</a> or via live chat.</p>

        <p>
          <a href="' . htmlspecialchars($app_url) .'vip" class="btn">Go to Dashboard</a>
        </p>

        <p class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name) . '. All rights reserved.</p>
      </div>
    </body>
    </html>
    ';

    $recipient = $row_user['email'] ?? null;
    if ($recipient) {
        sendEmail($recipient, $subject, $message);
    }

} catch (Exception $e) {
    $dbh->rollBack();
    die("Error saving payment: " . $e->getMessage());
}

// Redirect back to dashboard with toast
$_SESSION['toast'] = [
    'type'    => 'success',
    'message' => 'Wallet Top-up of ₦' . number_format($amount, 2) . ' was successful!'
];
header("Location: index");
exit;


