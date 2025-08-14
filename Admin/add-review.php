<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
  header("Location: ../login");
  exit();
}

// Fetch VIP users for dropdown
$vip_users_stmt = $dbh->prepare("SELECT id, full_name FROM users WHERE role = 'vip' ORDER BY full_name ASC");
$vip_users_stmt->execute();
$vip_users = $vip_users_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = trim($_POST['full_name'] ?? '');
  $rating = intval($_POST['rating'] ?? 0);
  $comment = trim($_POST['comment'] ?? '');

  if (empty($full_name) || $rating < 1 || $rating > 5 || empty($comment)) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required.'];
    header("Location: add-review");
    exit();
  }

  // Insert review
  $stmt = $dbh->prepare("INSERT INTO reviews (full_name, comment, rating,type) VALUES (?, ?, ?,?)");
  $stmt->execute([$full_name, $comment, $rating,'testimonial']);
  $record_id = $dbh->lastInsertId();
  // Log activity
  $action = "Added review for user: $full_name on $current_date";
  log_activity($dbh, $user_id, $action, 'reviews', $record_id, $ip_address);

  $_SESSION['toast'] = ['type' => 'success', 'message' => 'Review added successfully!'];
  header("Location: add-review");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Review - <?= htmlspecialchars($app_name ?? 'Admin Panel') ?></title>
  <?php include 'partials/head.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
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
    <h2 class="mb-4">Add User Review</h2>
    <div class="card shadow-sm p-4">
      <form method="POST">
        <div class="mb-3">
          <label for="user_id" class="form-label">Select VIP User</label>
          <select name="full_name" id="full_name" class="form-select" required>
            <option value="">-- Select User --</option>
            <?php foreach ($vip_users as $user): ?>
              <option value="<?= htmlspecialchars($user['full_name']) ?>"><?= htmlspecialchars($user['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="rating" class="form-label">Rating</label>
          <select name="rating" id="rating" class="form-select" required>
            <option value="">-- Select Rating --</option>
            <?php
              for ($i = 1; $i <= 5; $i++) {
                $stars = str_repeat('★', $i) . str_repeat('☆', 5 - $i);
                echo "<option value=\"$i\">$stars ($i)</option>";
              }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="comment" class="form-label">Comment</label>
          <textarea name="comment" id="comment" class="form-control summernote" rows="4" maxlength="120" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-send"></i> Submit Review</button>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('.summernote').summernote({
      height: 200,
      toolbar: [
        ['style', ['bold', 'italic', 'underline']],
        ['para', ['ul', 'ol']],
        ['insert', ['link']],
        ['view', ['codeview']]
      ]
    });
  });
</script>
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
