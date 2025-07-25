<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch review
$stmt = $dbh->prepare("SELECT r.*, u.full_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
$stmt->execute([$id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Review not found.'];
    header("Location: review-record");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = trim($_POST['comment'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);

    if ($comment === '' || $rating < 1 || $rating > 5) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid input.'];
        header("Location: edit-review?id=$id");
        exit;
    }

    $stmt = $dbh->prepare("UPDATE reviews SET comment = ?, rating = ? WHERE id = ?");
    $success = $stmt->execute([$comment, $rating, $id]);

    if ($success) {
        //activity log
        $action = "Updated review ID $id on $current_date";
        log_activity($dbh, $user_id, $action, 'reviews', $id, $ip_address);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Review updated successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update review.'];
    }

    header("Location: review-record");
    exit;
}

// Fetch average rating of the user
$avg_stmt = $dbh->prepare("SELECT AVG(rating) AS avg_rating FROM reviews WHERE user_id = ?");
$avg_stmt->execute([$row_review['user_id']]);
$avg_rating = round($avg_stmt->fetchColumn(), 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Review - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2>Edit Review</h2>
     <div class="mb-3">
      <strong>VIP User:</strong> <?= htmlspecialchars($review['full_name']) ?><br>
      <strong>Average Rating:</strong> <?= $avg_rating ?> / 5
    </div>
   <form action="" method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="comment" class="form-label">Comment</label>
        <textarea name="comment" id="comment" class="form-control" rows="4" required><?= htmlspecialchars($review['comment']) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="rating" class="form-label">Rating (1 to 5)</label>
        <select name="rating" id="rating" class="form-select" required>
          <option value="" disabled <?= !isset($review['rating']) ? 'selected' : '' ?>>-- Select Rating --</option>
          <?php for ($i = 1; $i <= 5; $i++): 
            $stars = str_repeat('★', $i) . str_repeat('☆', 5 - $i);
          ?>
            <option value="<?= $i ?>" <?= $review['rating'] == $i ? 'selected' : '' ?>>
              <?= $i ?> - <?= $stars ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Update Review</button>
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
