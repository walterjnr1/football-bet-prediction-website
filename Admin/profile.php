<?php
include('../inc/config.php');
include('../inc/functions.php'); // This should contain the `log_activity` function

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);

    $update = $dbh->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
    if ($update->execute([$full_name, $email, $phone, $user_id])) {
        
                   //  activity log
         $action = "Updated profile  on: $current_date"; 
          log_activity($dbh, $user_id, $action, 'users',$user_id, $ip_address);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile updated successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update profile.'];
    }
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Profile - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link rel="shortcut icon" href="../<?php echo $app_logo ; ?>" type="image/x-icon" />

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

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4">Admin Profile</h2>

    <div class="card shadow-sm p-4 text-center">
      <div class="initial-avatar">
        <?= strtoupper(substr($row_user['full_name'], 0, 1)) ?>
      </div>
      <h4 class="mt-3"><?= htmlspecialchars($row_user['full_name']) ?></h4>
      <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($row_user['email']) ?></p>
      <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($row_user['phone']) ?></p>
      <p class="mb-3"><strong>Status:</strong>
        <span class="badge bg-<?= 
            $row_user['status'] === 'active' ? 'success' : 
            ($row_user['status'] === 'inactive' ? 'warning' : 'danger') ?>">
          <?= ucfirst($row_user['status']) ?>
        </span>
      </p>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        <i class="bi bi-pencil-square"></i> Edit Profile
      </button>
    </div>
  </div>
</main>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" class="modal-content">
      <input type="hidden" name="update_profile" value="1">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Edit Admin Info</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($row_user['full_name']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row_user['email']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row_user['phone']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($row_user['status']) ?>" disabled>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Footer -->
<footer class="mt-5">
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toast (SweetAlert) -->
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
