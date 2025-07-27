<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Fetch VIP results ordered by most recent
$query = "SELECT * FROM vip_results ORDER BY result_date DESC";
$stmt = $dbh->prepare($query);
$stmt->execute();
$vip_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>VIP Result Records - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3">
  <?php include 'partials/nav.php'; ?>
</nav>

<!-- Sidebar -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenuMobile">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<!-- Main Content -->
<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-3">VIP Result Tickets</h2>

    <div class="card shadow-sm p-3">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Outcome</th>
              <th>Notes</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($vip_results): ?>
            <?php foreach ($vip_results as $i => $row): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['result_date']) ?></td>
                <td>
                  <?php
                    echo match($row['outcome']) {
                      'won' => '<span class="badge bg-success">Won</span>',
                      'lose' => '<span class="badge bg-danger">Lose</span>',
                      default => '<span class="badge bg-secondary">Unknown</span>',
                    };
                  ?>
                </td>
                <td><?= nl2br(htmlspecialchars($row['notes'] ?? '-')) ?></td>
                <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No VIP result records found.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
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
