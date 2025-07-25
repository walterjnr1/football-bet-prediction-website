<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if (!$row_user) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'VIP record not found.'];
    header("Location: vip-records");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Update query
    $sql = "UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $dbh->prepare($sql);
    $success = $stmt->execute([$fullname, $email, $phone, $id]);

    if ($success) {
        // Log activity
        $action = "updated vip (ID: $id) on: $current_date";
        log_activity($dbh, $user_id, $action, 'users', $id, $ip_address);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'VIP data updated successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update VIP data.'];
    }

    header("Location: vip-records");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit VIP - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
 <?php include 'partials/head.php'; ?>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3">
  <?php include 'partials/nav.php'; ?>
</nav>

<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenuMobile">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2>Edit VIP</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="title" class="form-label">Fullname</label>
        <input type="text" name="fullname" id="fullname" class="form-control" required value="<?= htmlspecialchars($row_user['full_name']) ?>">
      </div>

      <div class="mb-3">
        <label for="content" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($row_user['email']) ?>">
      </div>

      <div class="mb-3">
        <label for="published_at" class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($row_user['phone']) ?>">
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Update VIP</button>
      </div>
    </form>
  </div>
</main>

<footer class="footer">
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>
<?php include 'partials/summernote.php'; ?>
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
