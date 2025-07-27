<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
  header("Location: ../login");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current_password = $_POST['current_password'] ?? '';
  $new_password = $_POST['new_password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Validate current password
  if (!password_verify($current_password, $row_user['password_hash'])) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Current password is incorrect.'];
    header("Location: changepassword");
    exit();
  }

  if ($new_password !== $confirm_password) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'New passwords do not match.'];
    header("Location: changepassword");
    exit();
  }

  if (strlen($new_password) < 6) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Password must be at least 6 characters.'];
    header("Location: changepassword");
    exit();
  }

  // Update password
  $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
  $stmt = $dbh->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
  $stmt->execute([$new_hash, $user_id]);

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
        <p>This is a confirmation that your account password was recently changed to <strong>' . htmlspecialchars($new_password) . '</strong>. If you did not initiate this change, please contact our support team immediately.</p>
        <p>Thank you for keeping your account secure.</p>
        <a href="' . htmlspecialchars($app_url.'/Admin' ?? '#') . '" class="btn">Go to Dashboard</a>
      </div>
            <div class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name ?? 'Victory Fixed'). '. All rights reserved '  . '</div>

    </div>
  </body>
  </html>';

  sendEmail($row_user['email'], $subject, $message);
 //  activity log
         $action = "Changed Password on: $current_date"; 
          log_activity($dbh, $user_id, $action, 'users',$row_user['id'], $ip_address);

  $_SESSION['toast'] = ['type' => 'success', 'message' => 'Password changed successfully.'];
  header("Location: changepassword");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Change Password - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
  <?php include 'partials/nav.php'; ?>
</nav>

<!-- Sidebar (Desktop) -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<!-- Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<!-- Main Content -->
<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4">Change Password</h2>
    <div class="card shadow-sm p-4">
      <form method="POST">
        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password</label>
          <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>

        <div class="mb-3">
          <label for="new_password" class="form-label">New Password</label>
          <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>

        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm New Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-shield-lock"></i> Update Password</button>
      </form>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
