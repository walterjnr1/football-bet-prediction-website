<?php
include('config.php');
include('inc/email_landing_page.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name && $email && $subject && $message) {
        $mailSubject = "Contact Message: $subject";

        $mailBody = "
        <html><head><style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0; }
        .container { background: #fff; padding: 20px; margin: 30px auto; border-radius: 10px; max-width: 600px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { font-size: 16px; line-height: 1.6; color: #555; }
        .footer { margin-top: 20px; font-size: 13px; color: #888; }
        </style></head><body>
        <div class='container'>
            <h2>New Contact Message</h2>
            <p><strong>Name:</strong> ".htmlspecialchars($name)."</p>
            <p><strong>Email:</strong> ".htmlspecialchars($email)."</p>
            <p><strong>Subject:</strong> ".htmlspecialchars($subject)."</p>
            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            <div class='footer'>&copy; " . date('Y') . " " . htmlspecialchars($app_name) . "</div>
        </div>
        </body></html>
        ";

        $emailSent = sendEmail($app_email, $mailSubject, $mailBody);
       log_activity($dbh, $user_id, "Anonymous user sent a contact us message", '', '', $ip_address);

        if ($emailSent) {
                $_SESSION['toast'] = ['type'=>'success','message'=>'Message sent successfully!.'];
        } else {
         $_SESSION['toast'] = ['type'=>'error','message'=>'Failed to send message. Please try again.'];
        }
    } else {
         $_SESSION['toast'] = ['type'=>'error','message'=>'Please fill in all fields.'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contact Us - <?php echo $app_name; ?></title>
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

    <!-- Hero -->
    <div class="hero overlay" style="background-image: url('images/bg_3.jpg');">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-9 mx-auto text-center">
            <h1 class="text-white">Contact</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <form action="" method="post">
              <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
              </div>
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
              </div>
              <div class="form-group">
                <input type="text" name="subject" class="form-control" placeholder="Subject" required>
              </div>
              <div class="form-group">
                <textarea name="message" class="form-control" rows="8" placeholder="Write something..." required></textarea>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-primary py-3 px-5" value="Send Message">
              </div>
            </form>  
          </div>

          <!-- Contact Info -->
          <div class="col-lg-4 ml-auto">
            <ul class="list-unstyled">
              <li class="mb-2">
                <strong class="text-white d-block">Email</strong>
                <a href="mailto:<?php echo $app_email; ?>"><?php echo $app_email; ?></a>
              </li>
              <li class="mb-2">
                <strong class="text-white d-block">Phone</strong>
                <a href="#"><?php echo $row_website['whatsapp_phone']; ?></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- WhatsApp Button -->
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
