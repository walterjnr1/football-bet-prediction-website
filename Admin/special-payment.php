<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit();
}

$users = $dbh->query("SELECT id, full_name, email FROM users where role='vip' ORDER BY full_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$stages = [
    'initial' => 'Initial Payment',
    'clearance_fee' => 'Clearance Fee',
    'fifa_fee' => 'FIFA Fee',
    'delivery_fee' => 'Delivery Fee',
    'completed' => 'Completed'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $next_stage = $_POST['next_stage'] ?? null;

    if ($user_id && isset($stages[$next_stage])) {
        $exists = $dbh->prepare("SELECT COUNT(*) FROM vip_payment_stages WHERE user_id = ?");
        $exists->execute([$user_id]);

        if ($exists->fetchColumn() > 0) {
            $stmt = $dbh->prepare("UPDATE vip_payment_stages SET current_stage = ?, is_completed = ? WHERE user_id = ?");
            $stmt->execute([$next_stage, $next_stage === 'completed' ? 1 : 0, $user_id]);
        } else {
            $stmt = $dbh->prepare("INSERT INTO vip_payment_stages (user_id, current_stage, is_completed) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $next_stage, $next_stage === 'completed' ? 1 : 0]);
        }

        $_SESSION['toast'] = ['type'=>'success','message'=>'VIP Payment Stage updated successfully.'];
        header("Location:special-payment");
        exit();
    }

    $_SESSION['toast'] = ['type'=>'error','message'=>'Invalid data submitted.'];
    header("Location:special-payment");
    exit();
}

$records = $dbh->query("SELECT vps.*, u.full_name, u.email FROM vip_payment_stages vps JOIN users u ON vps.user_id = u.id ORDER BY vps.updated_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Confirm Payments - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2 class="mb-4">Confirm User Payments</h2>

    <div class="card shadow-sm p-4 mb-4">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Select User</label>
          <select name="user_id" class="form-select" required>
            <option value="">-- Choose User --</option>
            <?php foreach ($users as $user): ?>
              <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name'] . ' (' . $user['email'] . ')') ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Next Stage</label>
          <select name="next_stage" class="form-select" required>
            <option value="">-- Select Stage --</option>
            <?php foreach ($stages as $key => $label): ?>
              <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Update Stage</button>
      </form>
    </div>

    <div class="card shadow-sm p-4">
      <h5 class="mb-3">User Payment Stages</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>User</th>
            <th>Current Stage</th>
            <th>Status</th>
            <th>Last Updated</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['full_name']) ?> <small>(<?= htmlspecialchars($r['email']) ?>)</small></td>
              <td><span class="badge bg-info text-dark"><?= $stages[$r['current_stage']] ?? 'Unknown' ?></span></td>
              <td><?= $r['is_completed'] ? '<span class="text-success fw-bold">Completed</span>' : '<span class="text-warning">In Progress</span>' ?></td>
              <td><?= date('F j, Y H:i', strtotime($r['updated_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <p>&nbsp;</p>
</main>

<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
