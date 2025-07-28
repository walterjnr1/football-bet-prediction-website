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
            <h1 class="text-white">About Us</h1>
            <p>Fixed matches, Best site for fixed matches, Fixed matches today.</p>
          </div>
        </div>
      </div>
    </div><br> <br>



    <section class="about-section">
        <h1>About Us</h1>
        <div class="about-box">
          <p>
            Welcome to SureFixedWin — Your trusted platform for those who want more than just predictions.
          </p>
          <p>
            In today’s world, finding honesty and reliability around fixed matches can be challenging. That’s why we created SureFixedWin: a place built on transparency, expertise, and insider knowledge.

          </p>
          <p>
            After carefully analyzing other platforms and collaborating with our network of verified insiders, we’ve developed a system designed to bring you only the most carefully selected fixed games. Our trusted sources include individuals with direct connections inside clubs, giving us access to premium information you won’t find elsewhere.
          </p>
          <p>
            Through dedication and proven strategies, we aim to turn words into real results. Join us, stay connected, and watch your profits grow with SureFixedWin.
          </p>
        </div>
      </section>

      <section class="vip-section">
        <h2>Ready to Start Winning?</h2>
        <p>Join our VIP club today and get access to exclusive predictions and fixed odds.</p>
        <a href="Vip-Access" class="vip-button">Become a VIP Member</a>
      </section>
      <br> <br>
      
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