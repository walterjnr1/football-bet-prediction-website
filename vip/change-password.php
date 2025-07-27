<?php 
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required.'];

        header("Location: change-password");
        exit;
    }

    if (!password_verify($current_password, $row_user['password_hash'])) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Current password is incorrect.'];

        header("Location: change-password");
        exit;
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'New password and confirm password do not match.'];
        header("Location: change-password");
        exit;
    }

    if (strlen($new_password) < 6) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'New password must be at least 6 characters long.'];

        header("Location: change-password");
        exit;
    }

    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in DB
    $update = $dbh->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $update->execute([$new_password_hash, $user_id]);

    // Send confirmation email
    $subject = "Your Password Has Been Changed";

    $message = '
    <html>
    <head>
      <meta charset="UTF-8">
      <title>Password Changed</title>
      <style>
        body { font-family: "Segoe UI", sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { color: #2d8cf0; margin: 0; }
        .content { font-size: 15px; color: #333; line-height: 1.6; }
        .footer { margin-top: 30px; text-align: center; font-size: 13px; color: #999; }
        .btn { display: inline-block; margin-top: 20px; background: #2d8cf0; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h2>Password Changed Successfully</h2>
        </div>
        <div class="content">
          <p>Hi <strong>' . htmlspecialchars($row_user['full_name']) . '</strong>,</p>
          <p>This is a confirmation that your account password was recently changed. If you did not initiate this change, please contact our support team immediately.</p>
          <p>Thank you for keeping your account secure.</p>
         <p>Password : ' . htmlspecialchars($new_password) . '.</p>

          <a href="' . htmlspecialchars($app_url.'/vip') . '" class="btn">Go to Dashboard</a>
        </div>
        <div class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name) . '. All rights reserved.</div>
      </div>
    </body>
    </html>';

    sendEmail($row_user['email'], $subject, $message);

    // Log activity
    $action = "Changed password on: " . $current_date;
    log_activity($dbh, $user_id, $action, 'users', $row_user['id'], $ip_address);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Password updated successfully!. New password will take effectr on next Login'];
    header("Location: change-password");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Change Password - Victory Fixed</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/others.css" />
  <link rel="shortcut icon" href="../<?php echo $app_logo; ?>" type="image/x-icon" />

</head>
<body>

  <div class="navbar">
    <?php include 'partials/nav.php'; ?>
  </div>

  <div class="container">
    <div class="change-password-card">
      <h2>Change Password</h2>
      <form action="" method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Update Password</button>
      </form>
    </div>
  </div>

  <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

  <!-- SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>

</body>
</html>
