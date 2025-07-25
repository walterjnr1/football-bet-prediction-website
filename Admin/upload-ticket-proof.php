<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit();
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = '../uploadImage/Screenshots/';
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate extension
        if (!in_array($imageFileType, $validTypes)) {
            $_SESSION['toast'] = ['type'=>'error','message'=>'Invalid image format. Only JPG, JPEG, PNG, GIF allowed.'];
            header("Location: upload-ticket-proof");
            exit();
        }

        // Upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $relative_path = 'uploadImage/Screenshots/' . $file_name;

            $stmt = $dbh->prepare("INSERT INTO ticket_proofs (image, description) VALUES ( ?, ?)");
            $stmt->execute([ $relative_path, $description]);

            $_SESSION['toast'] = ['type'=>'success','message'=>'Ticket proof uploaded successfully.'];
        } else {
            $_SESSION['toast'] = ['type'=>'error','message'=>'Failed to upload image.'];
        }
    } else {
        $_SESSION['toast'] = ['type'=>'error','message'=>'Please select an image to upload.'];
    }

    header("Location: upload-ticket-proof");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Upload Ticket Proof</title>
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
    <h2 class="mb-4">Upload Ticket Proof</h2>
    <div class="card shadow-sm p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Ticket Screenshot (JPG, PNG, GIF)</label>
          <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(event)">
        </div>

        <div class="mb-3">
          <label class="form-label">Description (Optional)</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Preview</label>
          <img id="imagePreview" src="#" alt="Image Preview" style="max-height: 300px; display: none;" class="img-fluid rounded shadow">
        </div>

        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-upload"></i> Upload</button>
      </form>
    </div>
  </div>
</main>

<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('imagePreview');
        preview.src = reader.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
