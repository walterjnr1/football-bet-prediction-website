<?php 
include('config.php');
// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnreview'])) {
    $full_name = trim($_POST['full_name']);
    $comment = trim($_POST['comment']);
    $rating = intval($_POST['rating']);

    if (!empty($full_name) && !empty($comment) && $rating >= 1 && $rating <= 5) {
        $stmt = $dbh->prepare("INSERT INTO reviews (full_name, comment, rating) VALUES (?, ?, ?)");
        $stmt->execute([$full_name, $comment, $rating]);
        $review_id = $dbh->lastInsertId();
   
         // Log activity
         $action = "User added review on: $current_date"; 
          log_activity($dbh, $user['id'], $action, 'reviews',$user['id'], $ip_address);
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Thank you for your review!'];
        header("Location: index");

        exit;
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required and rating must be between 1-5.'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title> Welcome - <?php echo $app_name; ?></title>
  <?php include('partials/head.php'); ?>
</head>

<body>
  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar py-4" role="banner">
      <div class="container">
        <div class="d-flex align-items-center">
          <div class="site-logo">
            <a href="index">
              <img src="<?php echo $app_logo; ?>" alt="Logo">
            </a>
          </div>
          <div class="ml-auto">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <?php include('partials/navbar.php'); ?>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right text-white"><span class="icon-menu h3 text-white"></span></a>
          </div>
        </div>
      </div>
    </header>

    <div class="hero overlay" style="background-image: url('images/bg_3.jpg');">
      <?php include('partials/hero.php'); ?>
    </div> <br><br><br>

    <div class="vip-results">
      <?php include('partials/vip-result.php'); ?>
    </div><br><br><br>

    <div class="tips-container">
      <?php include('partials/tips.php'); ?>
    </div><br><br><br><br>

    <div class="vip-container">
      <?php include('partials/vip-container.php'); ?>
    </div>

    <div class="Review-heading">
      <p>Review From Our Members</p>
    </div>

    <div class="testimonial-container">
      <?php include('partials/testimonial-container.php'); ?>

      <div class="review-form">
        <h3>Leave a Review</h3>
        <form method="POST">
          <label>First Name</label>
          <input type="text" name="full_name" required />

          <label>Comment</label>
          <textarea name="comment" rows="5" maxlength="120"></textarea>

          <label>Rating</label>
          <div class="rating-options">
            <label><input type="radio" name="rating" value="1" required /><span>★</span><span class="rating-label">1</span></label>
            <label><input type="radio" name="rating" value="2" /><span>★</span><span class="rating-label">2</span></label>
            <label><input type="radio" name="rating" value="3" /><span>★</span><span class="rating-label">3</span></label>
            <label><input type="radio" name="rating" value="4" /><span>★</span><span class="rating-label">4</span></label>
            <label><input type="radio" name="rating" value="5" /><span>★</span><span class="rating-label">5</span></label>
          </div>

          <button type="submit" name="btnreview">Submit</button>
        </form>
      </div>
    </div>

    <script src="main.js"></script><br><br><br> 

    <section class="faq-section">
      <?php include('partials/faq.php'); ?>
    </section><br><br><br>

    <section class="fixed-matches-section">
      <?php include('partials/fixed-matches-section.php'); ?>
    </section><br><br><br>

    <div class="container site-section">
      <?php include('partials/blog.php'); ?>
    </div>

    <!-- Floating WhatsApp Button -->
          <?php include('partials/whatsapp.php'); ?>


    <footer class="footer-section">
      <?php include('partials/footer.php'); ?>
    </footer>
  </div>

  <!-- SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>
  <?php include('partials/script.php'); ?>
</body>
</html>
