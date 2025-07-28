<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Fetch user info
$user_id = $_SESSION['user_id'];
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
    <html><head><style>
        body { font-family: "Segoe UI", sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.05); }
        .header h2 { color: #2d8cf0; margin: 0; }
        .content { font-size: 15px; color: #333; line-height: 1.6; }
    </style></head><body>
    <div class="container">
      <div class="header"><h2>New Contact Message</h2></div>
      <div class="content">
        <p><strong>Full Name:</strong> ' . $fullname . '</p>
        <p><strong>Email:</strong> ' . $email . '</p>
        <p><strong>Phone:</strong> ' . ($phone ?: 'Not Provided') . '</p>
        <p><strong>Message:</strong><br>' . nl2br($message_content) . '</p>
      </div>
    </div></body></html>';

    $adminEmail = $row_website['site_email'] ?? 'support@victoryfixed.com';
    $sent = sendEmail($adminEmail, $subject, $message);

    log_activity($dbh, $user_id, "Sent contact message", 'users', $user_id, $_SERVER['REMOTE_ADDR']);

    $_SESSION['toast'] = $sent
        ? ['type' => 'success', 'message' => 'Message sent successfully!']
        : ['type' => 'error', 'message' => 'Failed to send. Try again later.'];

    header("Location: contact-us.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Change Password - Victory Fixed</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/others.css" />
  <link rel="shortcut icon" href="../<?php echo $app_logo; ?>" type="image/x-icon" />

</head>
<body style="font-family: Arial, sans-serif; margin: 0; background: #f4f4f4;">
  
  <div class="navbar">
    <?php include 'partials/nav.php'; ?>
  </div>

  <div style="max-width: 600px; background: white; margin: 30px auto; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <h2 style="text-align: center; color: #2d8cf0;">Send Us a Message</h2>
    <form method="POST" action="">
      <label>Full Name</label><br>
      <input type="text" name="fullname" required value="<?= htmlspecialchars($row_user['full_name']) ?>" style="width:100%; padding:10px; margin:10px 0;"><br>

      <label>Email</label><br>
      <input type="email" name="email" required value="<?= htmlspecialchars($row_user['email']) ?>" style="width:100%; padding:10px; margin:10px 0;"><br>

      <label>Phone</label><br>
      <input type="text" name="phone" value="<?= htmlspecialchars($row_user['phone']) ?>" style="width:100%; padding:10px; margin:10px 0;"><br>

      <label>Your Message</label><br>
      <textarea name="message" required rows="6" style="width:100%; padding:10px; margin:10px 0;"></textarea><br>

      <button type="submit" style="background: #2d8cf0; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer;">Send Message</button>
    </form>
  </div>

 <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

  <!-- SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>

</body>
</html>
