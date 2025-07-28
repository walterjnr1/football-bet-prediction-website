<?php
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Disclaimer - <?php echo $app_name; ?></title>
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
            <h1 class="text-white">Disclaimer</h1>
           
          </div>
        </div>
      </div>
    </div>

    <section class="terms-section">
     
    
      <div class="terms-box">
        <p>
            Disclaimer
            Welcome to <?php echo $app_name; ?>. Please read this important information carefully.

        </p>
    
    
        <h3>Insights, Not Guarantees
        </h3>
    
        <p>
          <strong></strong> All soccer predictions, correct score tips, fixed match claims, and live score updates shared on SureFixedWin (the “Website”) are offered to help inform and entertain you. While we aim for accuracy, outcomes in sports can never be guaranteed.

        </p>
    

        <h3>Betting Involves Risk
        </h3>
    
        <p>
          <strong></strong> Betting and gambling come with inherent risks. Past performance does not guarantee future results. Our analysis, statistics, and expert opinions are not financial or betting advice. Always use your own judgment before placing any wagers.

        </p>
    
      
        <h3>Fixed Matches – Proceed with Caution
        </h3>
    
        <p>
          <strong></strong> We do not encourage or endorse illegal or unethical betting practices. Claims about “fixed matches” should be approached carefully, and you should always do your own research. SureFixedWin accepts no responsibility for losses resulting from reliance on such information.


        </p>

        <h3>Age & Responsible Gambling

        </h3>
    
        <p>
          <strong></strong> This Website is intended only for users aged 18 and above (or the legal gambling age in your jurisdiction). We strongly advocate for responsible gambling. If you believe you might have a gambling problem, please seek help from a recognized responsible gambling organization.
        </p>
    
       
        <h3>Limitation of Liability
        </h3>
    
        <p>
          <strong></strong> By using this Website, you agree that <?php echo $app_name; ?>, its owners, affiliates, and partners shall not be held liable for any direct, indirect, or consequential losses—including financial losses—arising from your use of, or reliance on, the information provided. All betting decisions are your sole responsibility.

        </p>
    
        <h3>Third-Party Links
        </h3>
        <p>
          <strong></strong> Our Website may include links to third-party websites. We do not control or take responsibility for the content, services, or privacy policies of these external sites.
        </p>

        <h3> Updates to This Disclaimer
        </h3>
        <p>
          <strong></strong>  <?php echo $app_name; ?> reserves the right to update or revise this disclaimer at any time without prior notice. Continued use of the Website means you accept these terms.
        </p><br>

    <p> <i><b>N/B: If you do not agree with any part of this disclaimer, please do not use our services.</b></i></p> 
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