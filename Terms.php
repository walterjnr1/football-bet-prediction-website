<?php
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Terms & Conditions - <?php echo $app_name; ?></title>
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
            <h1 class="text-white">Terms & Conditions</h1>
           
          </div>
        </div>
      </div>
    </div>

    <section class="terms-section">
     
    
      <div class="terms-box">
        <p>
          These Terms and Conditions (“Terms”) govern your use of the Website and services provided by  <strong><?php echo $app_url; ?></strong>
        </p>
    
        <p>
          <?php echo $app_name; ?> offers carefully selected fixed correct score games sourced from trusted international and local contacts.

        </p>
    
        <p>
          We understand the risks of relying on unclear tournaments with limited transparency. Top leagues are monitored closely by media and governing bodies, unlike lesser-known championships where manipulation is more likely. Our aim is to share only high-quality, reliable information from our trusted sources — giving you the highest possible winning chances.

        </p>
    
        <h3>1. Use of the Website</h3>
    
        <p>
          <strong>i.</strong> This Website is intended for users aged 18 and above, or the legal age in your jurisdiction.

        </p>
    
        <p>
          <strong>ii.</strong> Users are responsible for complying with local laws, as certain activities may be restricted in some regions. Copying, reproducing, or distributing <?php echo $app_name; ?>’s content without explicit permission is strictly prohibited. For permission requests, please contact us directly.

        </p>
        <p>
          <strong>iii.</strong> Users should exercise discipline and limit participation to amounts they can afford to lose. <?php echo $app_name; ?> is not liable for any financial gains or losses. Users accept full responsibility for the results of their choices.
        </p>
        <p>
          <strong>iv.</strong> You are responsible for maintaining the confidentiality of your account and password.
        </p>
        <p>
          <strong>v.</strong> Payment is required before any service is delivered.

        </p>



        <h3>2. Predictions, Fixed Correct Scores, and Results
        </h3>
    
        <p>
          <strong>i.</strong> Our free game sessions are based on algorithms and data analysis, with a success rate typically between 80–85%
        </p>
    
        <p>
          <strong>ii.</strong> We also provide highly accurate correct score games sourced through verified contacts worldwide.
        </p>
        <p>
          <strong>iii.</strong> Payment must be made in advance for VIP correct score games. Post-service payment is not available.
        </p>
        



        <h3>3. ⁠Intellectual Property
        </h3>
    
        <p>
          <strong>i.</strong> The Website and all its content, software, and technology belong exclusively to SureFixedWin and are not licensed for external use.

        </p>
    
        <p>
          <strong>ii.</strong> Users agree not to copy, sell, modify, reproduce, or distribute our content in any form.
        </p>



        <h3>4. Changes to Terms
        </h3>
    
        <p>
          <strong>i.</strong> <?php echo $app_name; ?> reserves the right to update or change these Terms at any time without prior notice.

        </p>
    
        <p>
          <strong>ii.</strong> Continued use of the Website implies acceptance of the updated Terms.

        </p>
        <p>
          <strong>iii.</strong> Users must keep account passwords secure. SureFixedWin is not liable for any damages or losses resulting from misuse of your account.

        </p>
        <p>
          <strong>iv.</strong> Users must not use the Website for any illegal activities, including violation of copyright laws.
        </p>
        <p>
          <strong>v.</strong> Sharing or redistributing purchased information to third parties is strictly prohibited.
        </p>
        <p>
          <strong>vi.</strong> VIP password activation is provided at no additional cost.
        </p>
        <p>
          <strong>vii.</strong>  VIP users are authorized solely to access our fixed match services.
        </p>
        <p>
          <strong>viii.</strong>  Any violation of these Terms may result in immediate termination of services at SureFixedWin’s discretion.

        </p>
       



        <h3>5. Payment of Subscription
        </h3>
    
        <p>
          <strong>i.</strong> To access our VIP services, users must provide proof of payment. VIP access passwords are granted only after the user accepts these Terms. Subscription plans vary by package level (e.g., regular or VIP). SureFixedWin generally does not issue refunds unless you can demonstrate that, over 30 consecutive days of using our services with a Martingale system, you failed to generate profit.

        </p>
    
        <p>
          <strong>ii.</strong> Failure to pay any due amount within 3 calendar days may result in suspension of access without prior notice.
        </p>


        <h3>6. Refund Policy
        </h3>
        <p>
          <strong>i.</strong> Once your account is upgraded and the requested service is delivered, the service is considered provided, and no refunds will be issued. Services are offered to individuals aged 18 or older. Use responsibly.

        </p>


        <h3> 7. Modifications to Services and Prices
        </h3>
        <p>
          <strong>i.</strong>  Subscription prices may change with at least 10 days’ notice, which may be communicated via support channels or website updates.
        </p>

        <p>
          <strong>ii.</strong> Users may contact our live support on WhatsApp for questions about services, payments, or account status. We aim to respond as promptly as possible.
        </p> <br>

    <p> <i><b>N/B: By using this Website, you confirm that you have read, understood, and agree to these Terms.</b></i></p> 
      </div>
    </section>

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