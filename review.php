<?php
include('config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title> Review - <?php echo $app_name; ?></title>
  <?php include('partials/head.php'); ?>
</head>

<body>
  <div class="site-wrap">

    <!-- Navbar -->
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
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5 mx-auto text-center">
            <h1 class="text-white">Our Reviews</h1>
            <p>At <?php echo $app_name; ?> we bring you real-time match updates, team news, transfer rumors, and expert insights. Whether you’re checking scores, reading match previews, or analyzing player performance—we’ve got everything a true football fan needs..</p>
          </div>
        </div>
      </div>
    </div> <br> <br> <br>

    
    
    <section class="reviews-section">
  <h2>Reviews From Our Members</h2>
  <div class="reviews-container">
    <?php

    // Fetch latest 12 reviews
    $stmt = $dbh->prepare("SELECT full_name, comment, rating, created_at FROM reviews ORDER BY created_at DESC LIMIT 12");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($reviews):
      foreach ($reviews as $review):
        $stars = str_repeat("⭐", (int)$review['rating']);
        $date = date('F j, Y, g:i a', strtotime($review['created_at']));
    ?>
      <div class="review-card">
        <p>"<?= htmlspecialchars($review['comment']) ?>"</p>
        <p><strong>- <?= htmlspecialchars($review['fullname']) ?>.</strong></p>
        <div class="stars"><?= $stars ?></div>
        <span class="date"><?= $date ?></span>
      </div>
    <?php endforeach; else: ?>
      <p>No reviews found yet.</p>
    <?php endif; ?>
  </div>
</section>
<br><br><br><br>

    
   <!-- Floating WhatsApp Button -->
    <?php include('partials/whatsapp.php'); ?>

    <!-- Footer -->
    <footer class="footer-section">
      <?php include('partials/footer.php'); ?>
    </footer>
  </div>

  <!-- Scripts -->
  <?php include('partials/sweetalert.php'); ?>
  <?php include('partials/script.php'); ?>

  
</body>
</html>
