<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

// Handle VIP registration with subscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_vip'])) {
  $full_name = trim($_POST['full_name'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';
  $plan_id = intval($_POST['plan_id'] ?? 0);

  if (empty($full_name) || empty($phone) || empty($email) || empty($password) || empty($confirm_password) || $plan_id <= 0) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required including plan selection.'];
    header("Location: create-vip");
    exit();
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid email address.'];
    header("Location: create-vip");
    exit();
  }

  if (strlen($password) < 6) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Password must be at least 6 characters.'];
    header("Location: create-vip");
    exit();
  }

  if ($password !== $confirm_password) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Passwords do not match.'];
    header("Location: create-vip");
    exit();
  }

  $stmt = $dbh->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
  $stmt->execute([$phone, $email]);
  if ($stmt->rowCount() > 0) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'A VIP user with this phone or email already exists.'];
    header("Location: create-vip");
    exit();
  }

  $password_hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $dbh->prepare("INSERT INTO users (full_name, phone, email, password_hash, role, status) VALUES (?, ?, ?, ?, 'vip', 'active')");
  $stmt->execute([$full_name, $phone, $email, $password_hash]);
  $user_id = $dbh->lastInsertId();

  $stmt = $dbh->prepare("SELECT * FROM plans WHERE id = ?");
  $stmt->execute([$plan_id]);
  $plan = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$plan) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Selected plan not found.'];
    header("Location: create-vip");
    exit();
  }

  $stmt = $dbh->prepare("INSERT INTO subscriptions (user_id, plan_id,start_date,end_date) VALUES (?, ?, ?, ?)");
  $stmt->execute([$user_id, $plan_id, date('Y-m-d'), date('Y-m-d', strtotime("+{$plan['duration']} days"))]);

  $plans = $dbh->query("SELECT name, amount, duration FROM plans ORDER BY amount ASC")->fetchAll(PDO::FETCH_ASSOC);
  $plan_html = '<ul>';
  foreach ($plans as $p) {
    $plan_html .= "<li><strong>{$p['name']}</strong> - ₦" . number_format($p['amount']) . " for {$p['duration']} days</li>";
  }
  $plan_html .= '</ul>';

  $subject = "Welcome to VIP Fixed Matches!";
  $message = '<html><head><meta charset="UTF-8"><title>VIP Registration</title><style>
    body { font-family: "Segoe UI", sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
    .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .header { text-align: center; margin-bottom: 20px; }
    .header h2 { color: #28a745; }
    .content { font-size: 16px; color: #333; }
    .plans { background: #f1f1f1; padding: 15px; border-radius: 5px; }
    .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
    .btn { background-color: #28a745; color: white; padding: 10px 20px; display: inline-block; text-decoration: none; border-radius: 5px; }
  </style></head><body><div class="container">
    <div class="header"><h2>Welcome, ' . htmlspecialchars($full_name) . '!</h2></div>
    <div class="content">
      <p>Thank you for registering as a <strong>VIP member</strong> on <strong>' . htmlspecialchars($app_name ?? 'Victory Fixed') . '</strong>.</p>
      <p>You have been subscribed to the <strong>' . htmlspecialchars($plan['name']) . '</strong> plan.</p>
      <p>To explore other premium plans, view the options below:</p>
      <div class="plans">' . $plan_html . '</div>
      <a href="' . htmlspecialchars($app_url ?? '#') . '/vip-plans" class="btn">View More Plans</a>
    </div>
      <div class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name ?? 'Victory Fixed'). '. All rights reserved'  . '</div>
  </div></body></html>';

  sendEmail($email, $subject, $message);
  //  activity log
         $action = "created a VIP user with subscription $full_name on: $current_date"; 
          log_activity($dbh, $user_id, $action, 'users',$user_id, $ip_address);
  $_SESSION['toast'] = ['type' => 'success', 'message' => 'VIP registered and subscribed successfully. Email sent.'];
  header("Location: create-vip");
  exit();
}

// Fetch plans for form
$all_plans = $dbh->query("SELECT * FROM plans ORDER BY amount ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>VIP Registration - <?= htmlspecialchars($app_name ?? 'Victory Fixed') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
  <?php include 'partials/nav.php'; ?>
</nav>

<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4 text-success">VIP Registration & Subscription</h2>
    <div class="card shadow-sm p-4 mb-5">
      <form method="POST">
        <input type="hidden" name="register_vip" value="1">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" name="full_name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" class="form-control" name="phone" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Plan</label>
          <select name="plan_id" class="form-select" required>
            <option value="">-- Select Plan --</option>
            <?php foreach ($all_plans as $plan): ?>
              <option value="<?= $plan['id'] ?>"><?= htmlspecialchars($plan['name']) ?> - ₦<?= number_format($plan['amount']) ?> (<?= $plan['duration'] ?> days)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-person-plus"></i> Register & Subscribe</button>
      </form>
    </div>
  </div>
</main>

<footer class="mt-5">
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
