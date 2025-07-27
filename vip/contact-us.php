<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $dbh->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$row_user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message_content = htmlspecialchars(trim($_POST['message']));

    $subject = "New Contact Message from $fullname";

    $message = '
    <html>
    <head>
      <meta charset="UTF-8">
      <title>New Contact Message</title>
      <style>
        body { font-family: "Segoe UI", sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { color: #2d8cf0; margin: 0; }
        .content { font-size: 15px; color: #333; line-height: 1.6; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h2>New Contact Message</h2>
        </div>
        <div class="content">
          <p><strong>Full Name:</strong> ' . $fullname . '</p>
          <p><strong>Email:</strong> ' . $email . '</p>
          <p><strong>Phone:</strong> ' . ($phone ? $phone : 'Not Provided') . '</p>
          <p><strong>Message:</strong><br>' . nl2br($message_content) . '</p>
        </div>
      </div>
    </body>
    </html>';

    // Send email to site support
    $adminEmail = $row_website['site_email'] ?? 'support@victoryfixed.com';
    $sent = sendEmail($adminEmail, $subject, $message);

    // Log activity
    $action = "Submitted a contact message.";
    log_activity($dbh, $user_id, $action, 'users', $user_id, $ip_address);

    if ($sent) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Message sent successfully! We will get back to you shortly.'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Message failed to send. Please try again later.'];
    }

    header("Location: contact-us.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - <?php echo htmlspecialchars($app_name); ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="shortcut icon" href="../<?php echo $app_logo; ?>" type="image/x-icon" />

</head>
<body>

  <!-- NAVBAR -->
  <div class="navbar">
    <?php include 'partials/nav.php'; ?>
  </div>

  <!-- CONTACT FORM SECTION -->
  <div class="container-contact">
    <h2>Contact Us</h2>
    <form action="" method="POST">
      <input type="text" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($row_user['full_name']); ?>" required>
      <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($row_user['email']); ?>" required>
      <input type="tel" name="phone" placeholder="Phone Number (optional)">
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit"><i class="fas fa-paper-plane"></i> Send Message</button>
    </form>

    <div class="contact-info">
      <p>Email: <?php echo htmlspecialchars($row_website['site_email'] ?? 'support@victoryfixed.com'); ?></p>
      <p>Phone: <?php echo htmlspecialchars($row_website['whatsapp_phone'] ?? '+234 801 234 5678'); ?></p>
      <p>Website: <?php echo htmlspecialchars($row_website['site_url'] ?? 'https://victoryfixed.com'); ?></p>
    </div>
  </div>

  <!-- ✅ SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>

  <!-- FOOTER -->
  <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

</body>
</html>
