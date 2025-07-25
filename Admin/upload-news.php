<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
  header("Location: ../login");
}

$user_id = $_SESSION['user_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
  $content = $_POST['content'] ?? '';
  $image = '';

  // Upload image
  if (!empty($_FILES['image']['name'])) {
    $upload_dir = '../uploadImage/news/';
    if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0755, true);
    }
    $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      $image = 'uploadImage/news/' . $file_name;
    }
  }

  // Save to DB
  $stmt = $dbh->prepare("INSERT INTO news (slug, title, content, image) VALUES (?, ?, ?, ?)");
  $success = $stmt->execute([$slug, $title, $content, $image]);

  if ($success) {
    $news_id = $dbh->lastInsertId();
    $action = "Added news: $title";
    log_activity($dbh, $user_id, $action, 'news', $news_id, $ip_address);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'News uploaded successfully!'];
  } else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to upload news.'];
  }

  header("Location: upload-news");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Upload News - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2 class="mb-4">Upload News</h2>

    <div class="card shadow-sm p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="title" class="form-label fw-bold">News Title</label>
          <input type="text" class="form-control" name="title" id="title" required />
        </div>

        <div class="mb-3">
          <label for="image" class="form-label fw-bold">Upload Image</label>
          <input type="file" class="form-control" name="image" id="image" accept="image/*" />
          <div id="preview-container" style="display:none;">
            <img id="image-preview" src="#" alt="Image Preview" class="img-fluid rounded mt-3 border" style="max-height: 200px;" />
          </div>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label fw-bold">News Content</label>
        <textarea name="content" id="content" class="form-control summernote" rows="5" required><?= htmlspecialchars($row_news['content']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Post News</button>
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
<?php include 'partials/summernote.php'; ?>

<script>
  $(document).ready(function () {
    
    $('#image').change(function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          $('#image-preview').attr('src', e.target.result);
          $('#preview-container').show();
        };
        reader.readAsDataURL(file);
      } else {
        $('#image-preview').attr('src', '#');
        $('#preview-container').hide();
      }
    });
  });
</script>

<!-- Toast Alert -->
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
