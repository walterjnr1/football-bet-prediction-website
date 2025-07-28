<?php 
include('config.php');
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
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5 mx-auto text-center">
            <h1 class="text-white">Free Matches</h1>
            <p>100% fixed correct scores, sure odds, latest match results, and football analysis.</p>
          </div>
        </div>
      </div>
    </div> <br> <br>

    
    

     <div class="tips-container">
      <?php include('partials/tips.php'); ?>
    </div><br><br><br><br>

   
<!-- LIVESCORE WIDGET SOCCERSAPI.COM -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <title> Live Football Matches - <?php echo $app_name; ?></title>
 <link rel="stylesheet" href="css/result.css">
</head>
<body>

  <iframe class="live-score-frame" 
          src="https://www.scorebat.com/embed/livescore/" 
          allowfullscreen 
          allow="autoplay; fullscreen">
  </iframe>

</body>
</html> <br> <br>



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