<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing news record
$stmt = $dbh->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$row_news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row_news) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'News record not found.'];
    header("Location: news-record");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $published_at = $_POST['published_at'] ?? date('Y-m-d H:i:s');

    $imagePath = $row_news['image']; // keep old image by default

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploadImage/news/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $imagePath = $targetDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Image upload failed.'];
            header("Location: edit-news?id=$id");
            exit;
        }
    }

    // Update query
    $sql = "UPDATE news SET title = ?, content = ?, published_at = ?, image = ? WHERE id = ?";
    $stmt = $dbh->prepare($sql);
    $success = $stmt->execute([$title, $content, $published_at, $imagePath, $id]);

    if ($success) {
        // Log activity
        $action = "updated News (ID: $id) on: $current_date";
        log_activity($dbh, $user_id, $action, 'news', $id, $ip_address);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'News updated successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update news.'];
    }

    header("Location: news-record");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit News - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2>Edit News</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" required value="<?= htmlspecialchars($row_news['title']) ?>">
      </div>

      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea name="content" id="content" class="form-control summernote" rows="5" required><?= htmlspecialchars($row_news['content']) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="published_at" class="form-label">Published Date</label>
        <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($row_news['published_at'])) ?>">
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">Image (optional)</label>
        <input type="file" name="image" id="image" class="form-control">
        <?php if (!empty($row_news['image'])): ?>
          <img src="../<?= htmlspecialchars($row_news['image']) ?>" alt="current image" style="max-height: 100px; margin-top: 10px;">
        <?php endif; ?>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Update News</button>
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
