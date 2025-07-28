<?php 
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title> VIP Access - <?php echo $app_name; ?></title>
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
            <h1 class="text-white">VIP Club</h1>
            <p>Join our VIP Club for access to fixed odds and expert predictions.</p>
          </div>
        </div>
      </div>
    </div> <br> 

    <section class="vip-options">
        <div class="vip-card">
          <h2>Need VIP Access?</h2>
          <p>Contact our admin to get your exclusive VIP login credentials.</p>
          <a href="https://wa.me/<?php echo $row_website['whatsapp_phone']; ?>?text=Hello i am interested in your VIP access." class="btn-blue">Contact Admin</a>
        </div>
    
        <div class="vip-card">
          <h2>Already a VIP Member?</h2>
          <p>Access your exclusive fixed odds now.</p> <br>
          <a href="#" class="btn-green">Login to VIP Area</a>
        </div>
    </section>
     <br> <br> <br><br>
    
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