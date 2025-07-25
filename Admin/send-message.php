<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
  header("Location: ../login");
  exit();
}

// Fetch all VIP users
$stmt = $dbh->prepare("SELECT id, full_name, email FROM users WHERE role = 'vip' ORDER BY full_name ASC");
$stmt->execute();
$vip_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');
  $selected_user_id = $_POST['user_id'] ?? 'all';

  if (empty($subject) || empty($message)) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Subject and message are required.'];
    header("Location: send-message");
    exit();
  }

  $created_at = date('Y-m-d H:i:s');
  $sent_to_all = ($selected_user_id === 'all');

  function buildEmailTemplate($full_name, $subject, $message, $app_name = "Victory Fixed") {
    return '
    <html>
    <head>
      <style>
        body {
          font-family: "Segoe UI", sans-serif;
          background-color: #f4f4f4;
          margin: 0;
          padding: 0;
        }
        .container {
          background-color: #ffffff;
          max-width: 600px;
          margin: 30px auto;
          border-radius: 8px;
          overflow: hidden;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
          background-color: #0d6efd;
          color: white;
          padding: 20px;
          text-align: center;
        }
        .body {
          padding: 30px 20px;
          color: #333;
          font-size: 16px;
        }
        .footer {
          padding: 20px;
          background-color: #f1f1f1;
          font-size: 13px;
          text-align: center;
          color: #555;
        }
        .btn {
          display: inline-block;
          padding: 10px 20px;
          margin-top: 20px;
          background-color: #0d6efd;
          color: white;
          text-decoration: none;
          border-radius: 4px;
        }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h2>' . htmlspecialchars($app_name) . '</h2>
        </div>
        <div class="body">
          <p>Hi <strong>' . htmlspecialchars($full_name) . '</strong>,</p>
          <p>' . nl2br(htmlspecialchars($message)) . '</p>
          <p style="margin-top:30px;">Regards,<br><strong>' . htmlspecialchars($app_name) . ' Team</strong></p>
        </div>
        <div class="footer">
          You received this message because you are a VIP user of ' . htmlspecialchars($app_name) . '.<br>
          Please do not reply directly to this email.
        </div>
      </div>
    </body>
    </html>';
  }

  if ($sent_to_all) {
    foreach ($vip_users as $user) {
      $stmt = $dbh->prepare("INSERT INTO vip_messages (subject, message, user_id) VALUES (?, ?, ?)");
      $stmt->execute([$subject, $message, $user['id']]);

      $email_message = buildEmailTemplate($user['full_name'], $subject, $message, $app_name ?? 'Victory Fixed');
      sendEmail($user['email'], $subject, $email_message);
    }
  } else {
    $user_id = (int) $selected_user_id;
    $stmt_user = $dbh->prepare("SELECT * FROM users WHERE id = ? AND role = 'vip'");
    $stmt_user->execute([$user_id]);
    $vip_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if (!$vip_user) {
      $_SESSION['toast'] = ['type' => 'error', 'message' => 'Selected VIP user not found.'];
      header("Location: send-message");
      exit();
    }

    $stmt = $dbh->prepare("INSERT INTO vip_messages (subject, message, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$subject, $message, $user_id]);

    $email_message = buildEmailTemplate($vip_user['full_name'], $subject, $message, $app_name ?? 'Victory Fixed');
    sendEmail($vip_user['email'], $subject, $email_message);
  }

  $action = "Sent VIP message: \"$subject\" to " . ($sent_to_all ? 'all VIPs' : 'VIP ID ' . $user_id);
  log_activity($dbh, $_SESSION['user_id'], $action, 'vip_messages', null, $ip_address);

  $_SESSION['toast'] = ['type' => 'success', 'message' => 'VIP message sent successfully.'];
  header("Location: send-message");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Send VIP Message - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
  <?php include 'partials/nav.php'; ?>
</nav>

<!-- Sidebars -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>
<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<!-- Main -->
<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4">Send VIP Message</h2>

    <div class="card shadow-sm p-4">
      <form method="POST">
        <div class="mb-3">
          <label for="user_id" class="form-label">Recipient</label>
          <select class="form-select" name="user_id" id="user_id" required>
            <option value="all">All VIP Users</option>
            <?php foreach ($vip_users as $user): ?>
              <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="subject" class="form-label">Subject</label>
          <input type="text" name="subject" id="subject" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea name="message" id="message" class="form-control" rows="6" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Send Message</button>
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
