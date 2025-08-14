<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
  header("Location: ../login");
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'] ?? '';
    $site_email = $_POST['site_email'] ?? '';
    $site_url = $_POST['site_url'] ?? '';
    $whatsapp_phone = $_POST['whatsapp_phone'] ?? '';
    $logo = $row_website['logo']; // Default to existing logo
    $paystack_secret_key = $_POST['paystack_secret_key'] ?? '';
    $paystack_public_key = $_POST['paystack_public_key'] ?? '';

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $upload_dir = '../uploadImage/Logo/';
        $file_name = uniqid() . '_' . basename($_FILES['logo']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo = 'uploadImage/Logo/' . $file_name; // Store relative path
        }
    }

    // Update DB
    $update = $dbh->prepare("UPDATE website_settings SET site_name = ?,paystack_secret_key=?,paystack_public_key=?, site_email = ?, logo = ?, site_url = ?, whatsapp_phone = ? WHERE id = 1");
    $success = $update->execute([$site_name,$paystack_secret_key,$paystack_public_key, $site_email, $logo, $site_url, $whatsapp_phone]);


    if ($success) {
          //  activity log
         $action = "Updated Website data on: $current_date"; 
          log_activity($dbh, $user_id, $action, 'website_settings',$row_website['id'], $ip_address);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Settings updated successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update settings!'];
    }

    header("Location: website-settings");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Website Settings - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2 class="mb-4">Website Settings</h2>
    <div class="card shadow-sm p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3 text-center">
          <label class="form-label fw-bold">Current Logo</label><br />
          <img src="../<?= !empty($row_website['logo']) ? $row_website['logo'] : 'https://via.placeholder.com/120x80?text=Logo'; ?>" id="logoPreview" class="logo-preview img-thumbnail" alt="Logo Preview" width="120" />
          <input type="file" class="form-control mt-2" name="logo" id="logoUpload" accept="image/*">
        </div>

        <div class="mb-3">
          <label for="site_name" class="form-label">App Name</label>
          <input type="text" class="form-control" name="site_name" id="site_name" value="<?= htmlspecialchars($row_website['site_name']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="site_email" class="form-label">Support Email</label>
          <input type="email" class="form-control" name="site_email" id="site_email" value="<?= htmlspecialchars($row_website['site_email']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="site_url" class="form-label">Website URL</label>
          <input type="text" class="form-control" name="site_url" id="site_url" value="<?= htmlspecialchars($row_website['site_url']) ?>">
        </div>

        <div class="mb-3">
          <label for="whatsapp_phone" class="form-label">WhatsApp Number</label>
          <input type="tel" class="form-control" name="whatsapp_phone" id="whatsapp_phone" value="<?= htmlspecialchars($row_website['whatsapp_phone']) ?>">
        </div>

         <div class="mb-3">
          <label for="paystack_public_key" class="form-label">Paystack Public Key</label>
          <input type="text" class="form-control" name="paystack_public_key" id="paystack_public_key" value="<?= htmlspecialchars($row_website['paystack_public_key']) ?>">
        </div>
         <div class="mb-3">
          <label for="paystack_secret_key" class="form-label">Paystack Secret Key</label>
          <input type="text" class="form-control" name="paystack_secret_key" id="paystack_secret_key" value="<?= htmlspecialchars($row_website['paystack_secret_key']) ?>">
        </div>
        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-save"></i> Save Settings</button>
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

<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Preview uploaded logo
  document.getElementById('logoUpload').addEventListener('change', function (event) {
    const reader = new FileReader();
    reader.onload = function () {
      document.getElementById('logoPreview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  });
</script>

<!-- Toast (SweetAlert) -->
<?php include 'partials/sweetalert.php'; ?>

</body>
</html>
