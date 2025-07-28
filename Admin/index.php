<?php 
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
header("Location: ../login");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard - <?php echo $app_name?></title>
<?php include 'partials/head.php'; ?>


</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
<?php include 'partials/navbar.php'; ?>
</nav>

<!-- Offcanvas Sidebar for Mobile -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu">
<?php include 'partials/mobile-sidebar.php'; ?>
</div>

<!-- Desktop Sidebar -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
<?php include 'partials/desktop-sidebar.php'; ?>
</div>

<!-- Main Content -->
<main class="pt-5 mt-4 mb-5">
  <div class="container-fluid mt-4">
    <p class="welcome-message">👋 Welcome back, <?php echo $row_user['full_name']; ?></p>
    <h2 class="mb-4">Dashboard Overview</h2>
    <div class="row g-4 mb-5">
      <div class="col-lg-4 col-sm-6">
        <div class="analytics-card analytics-vip">
          <i class="lni lni-users"></i>
          <span class="fw-semibold fs-5">Total VIP Members</span>
          <span class="fs-3 fw-bold"><?php echo $no_vip['total']; ?></span>
          <a href="#" class="text-white">View Members</a>
        </div>
      </div>
      <div class="col-lg-4 col-sm-6">
  <div class="analytics-card analytics-visit">
    <i class="lni lni-stats-up"></i> 
    <span class="fw-semibold fs-5">Total Predictions</span>
          <span class="fs-3 fw-bold"><?php echo $no_predictions['total']; ?></span>
    <a href="#" class="text-white">View Traffic</a>
  </div>
</div>



<div class="col-lg-4 col-sm-6">
  <div class="analytics-card analytics-revenue text-white">
    <i class="lni lni-ticket me-2 text-white"></i> 
    <span class="fw-semibold fs-5">Total Predictions Won</span>
          <span class="fs-3 fw-bold"><?php echo $no_predictions_won['total']; ?></span>
    <a href="#" class="text-white">View Traffic</a>
  </div>
</div>

    </div>

    <h4 class="mb-3">Recent Registered Users</h4>
    <div class="row g-4 mb-5">
  <?php
  $vipQuery = "SELECT full_name, email, phone, image, status FROM users WHERE role = 'vip' ORDER BY id DESC LIMIT 8";
  $stmt = $dbh->prepare($vipQuery);
  $stmt->execute();
  $vipUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($vipUsers) {
    foreach ($vipUsers as $user) {
      $name = htmlspecialchars($user['full_name']);
      $email = htmlspecialchars($user['email']);
      $phone = htmlspecialchars($user['phone']);
      $status = strtolower($user['status']);
      $image = !empty($user['image']) ? htmlspecialchars($user['image']) : 'https://randomuser.me/api/portraits/men/1.jpg';

      // Define badge color and online indicator
      $badgeColor = $status === 'active' ? 'success' : 'secondary';
      $onlineDot = $status === 'active' ? '<span class="online-dot bg-success"></span>' : '<span class="online-dot bg-secondary"></span>';
  ?>
      <div class="col-md-3 col-sm-6">
        <div class="card user-card text-center shadow-sm p-3 position-relative">
          <?= $onlineDot ?>
          <img src="../<?= $image ?>" alt="<?= $name ?>" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
          <h6 class="mt-2"><?= $name ?></h6>
          <small>📞 <?= $phone ?></small><br>
          <small><?= $email ?></small><br>
          <span class="badge bg-<?= $badgeColor ?> mt-2 text-capitalize"><?= $status ?></span>
        </div>
      </div>
  <?php
    }
  } else {
    echo '<div class="col-12"><p class="text-center">No VIP users found.</p></div>';
  }
  ?>
</div>


    <?php
$sql = "SELECT * FROM activity_logs ORDER BY action_time DESC LIMIT 10";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Recent Activities</h4>
<ul class="list-group mb-5">
  <?php if (!empty($activities)): ?>
    <?php foreach ($activities as $activity): ?>
      <li class="list-group-item">
        <?php
          // Optional: customize icons or text based on action
          $icon = match($activity['action']) {
            'create' => '✅',
            'update' => '✍️',
            'delete' => '🗑️',
            'message' => '📩',
            default => '🔔'
          };
          echo "$icon " . ucfirst($activity['action']) . " on <strong>" . htmlspecialchars($activity['table_name']) . " table" . "</strong>";
        ?>
        <br>
        <small class="text-muted">At <?= date('Y-m-d H:i:s', strtotime($activity['action_time'])) ?></small>
      </li>
    <?php endforeach; ?>
  <?php else: ?>
    <li class="list-group-item text-muted">No recent activities found.</li>
    <p>
      <?php endif; ?>
</p>
    <p>&nbsp;    </p>
</ul>

  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container">
<?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
