<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe_vip'])) {
    $user_id = intval($_POST['user_id'] ?? 0);
    $plan_id = intval($_POST['plan_id'] ?? 0);

    if ($user_id <= 0 || $plan_id <= 0) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'VIP and plan must be selected.'];
        header("Location: add-subscription");
        exit();
    }


    if (!$row_user) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'VIP user not found.'];
        header("Location: add-subscription");
        exit();
    }

    $stmt = $dbh->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->execute([$plan_id]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$plan) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Selected plan does not exist.'];
        header("Location: add-subscription");
        exit();
    }

    $stmt = $dbh->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND plan_id = ? ORDER BY end_date DESC LIMIT 1");
    $stmt->execute([$user_id, $plan_id]);
    $existingSub = $stmt->fetch(PDO::FETCH_ASSOC);

    $today = date('Y-m-d');
    $start_date = $today;
    $duration = intval($plan['duration']);

    if ($existingSub && $existingSub['end_date'] >= $today) {
        $start_date = $existingSub['end_date'];
        $end_date = date('Y-m-d', strtotime("$start_date +$duration days"));
        $stmt = $dbh->prepare("UPDATE subscriptions SET end_date = ? WHERE id = ?");
        $stmt->execute([$end_date, $existingSub['id']]);
        $subscription_id = $existingSub['id'];
    } else {
        $end_date = date('Y-m-d', strtotime("$start_date +$duration days"));
        $stmt = $dbh->prepare("INSERT INTO subscriptions (user_id, plan_id, start_date, end_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $plan_id, $start_date, $end_date]);
        $subscription_id = $dbh->lastInsertId();
    }

    $subject = "Your VIP Subscription is Active!";
    $message = '<html><head><meta charset="UTF-8"><style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
    .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
    .header { background: #28a745; color: #fff; padding: 10px 20px; border-radius: 6px 6px 0 0; text-align: center; }
    .content { padding: 20px; color: #333; }
    .footer { font-size: 12px; color: #777; text-align: center; padding-top: 20px; }
    </style></head><body>
    <div class="container">
      <div class="header"><h2>Subscription Confirmed</h2></div>
      <div class="content">
        <p>Dear ' . htmlspecialchars($row_user['full_name']) . ',</p>
        <p>Your VIP subscription to the <strong>' . htmlspecialchars($plan['name']) . '</strong> plan has been successfully activated.</p>
        <p>Amount: N' . number_format($plan['amount']) . '<br>
        Duration: ' . $plan['duration'] . ' days<br>
        Expiry Date: ' . $end_date . '</p>
        <p>Enjoy your premium access!</p>
      </div>
      <div class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name ?? 'Victory Fixed'). '. All rights reserved '  . '</div>

    </div>
  </body></html>';

    sendEmail($row_user['email'], $subject, $message);

    $action = "Updated VIP plan on: $current_date";
    log_activity($dbh, $user_id, $action, 'subscriptions', $subscription_id, $ip_address);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Subscription successful and email sent.'];
    header("Location: add-subscription");
    exit();
}

$vipList = $dbh->query("SELECT id, full_name FROM users WHERE role = 'vip' ORDER BY full_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$plans = $dbh->query("SELECT id, name, amount, duration FROM plans ORDER BY amount ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <title>Subscribe VIP - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2 class="mb-4">VIP Subscription</h2>
    <div class="card shadow-sm p-4">
     <form id="subscriptionForm" method="POST">
        <input type="hidden" name="subscribe_vip" value="1">
        <div class="mb-3">
          <label class="form-label">Select VIP User</label>
          <select name="user_id" class="form-select" required>
            <option value="">-- Select VIP User --</option>
            <?php foreach ($vipList as $vip): ?>
              <option value="<?= $vip['id'] ?>"><?= htmlspecialchars($vip['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Plan</label>
          <select name="plan_id" class="form-select" required>
            <option value="">-- Select Plan --</option>
            <?php foreach ($plans as $plan): ?>
              <option value="<?= $plan['id'] ?>">
                <?= htmlspecialchars($plan['name']) ?> - ₦<?= number_format($plan['amount']) ?> (<?= $plan['duration'] ?> days)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#confirmModal">
          Subscribe
        </button>
      </form>
    </div>
  </div>
</main>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Subscription</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to subscribe this VIP user to the selected plan?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirmSubscribe">Yes, Subscribe</button>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<?php include 'partials/sweetalert.php'; ?>
<script>
document.getElementById('confirmSubscribe').addEventListener('click', function () {
    document.getElementById('subscriptionForm').submit();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
