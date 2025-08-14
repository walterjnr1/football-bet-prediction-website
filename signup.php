<?php
include('config.php');
include('inc/email_landing_page.php'); // for sendEmail function

$countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
    'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
    'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia',
    'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica',
    'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador','England', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini (fmr. Swaziland)', 'Ethiopia', 'Fiji',
    'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
    'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan',
    'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein',
    'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
    'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (formerly Burma)', 'Namibia', 'Nauru', 'Nepal',
    'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine State',
    'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
    'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
    'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka',
    'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago',
    'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
    'Yemen', 'Zambia', 'Zimbabwe','Wales','Scotland', 'Northern Ireland', 'Republic of Ireland'
];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_vip'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $country = trim($_POST['country']);

    // Validate input
    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($country)) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required.'];
        header("Location: signup");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid email address.'];
      header("Location: signup");
        exit;
    }

    // Check if email already exists
    $stmt = $dbh->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Email already registered.'];
      header("Location: signup");
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $dbh->prepare("INSERT INTO users (full_name, email, phone, password_hash,country) VALUES (?, ?, ?,?, ?)");
    $stmt->execute([$fullname, $email, $phone, $password_hash,$country]);
    $user_id = $dbh->lastInsertId();

    // Send welcome email
    $subject = "Welcome to $app_name - VIP Registration Successful";
    $message = '
    <html>
    <head>
      <meta charset="UTF-8">
      <title>Welcome to ' . htmlspecialchars($app_name) . '</title>
      <style>
        body { font-family: "Segoe UI", sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { color: #2d8cf0; margin: 0; }
        .content { font-size: 15px; color: #333; line-height: 1.6; }
        .footer { margin-top: 30px; text-align: center; font-size: 13px; color: #999; }
        .btn { display: inline-block; margin-top: 20px; background: #2d8cf0; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h2>Welcome to ' . htmlspecialchars($app_name) . '</h2>
        </div>
        <div class="content">
          <p>Hi <strong>' . htmlspecialchars($fullname) . '</strong>,</p>
          <p>We are excited to have you join our VIP members community. Please take note of the following:</p>
          <ul>
            <li>You must be <strong>18+</strong> years old to use our services.</li>
            <li>Stake only what you can afford to lose.</li>
            <li>Our predictions are <strong>not 100% guaranteed</strong> — we can win and lose.</li>
            <li>For better results, we advise subscribing to our premium predictions.</li>
          </ul>
          <p><strong>Your Password:</strong> ' . htmlspecialchars($password) . '</p>
          <a href="' . htmlspecialchars($app_url . '/vip/index') . '" class="btn">Go to Dashboard</a>
        </div>
        <div class="footer">&copy; ' . date('Y') . ' ' . htmlspecialchars($app_name) . '. All rights reserved.</div>
      </div>
    </body>
    </html>';

    sendEmail($email, $subject, $message);

    // Log activity
    $action = "Registered as VIP on: " . $current_date;
    log_activity($dbh, $user_id, $action, 'users', $user_id, $_SERVER['REMOTE_ADDR']);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Registration successful! Welcome email sent.'];
    header("Location: signup");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>VIP Registration - <?php echo $app_name; ?></title>
<?php include('partials/head.php'); ?>

</head>
<body>
    <div class="topbar">
        <?php include('partials/topbar.php')  ?>
    </div>
    <div class="logo">
        <img src="<?php echo $app_logo; ?>" alt="Logo">
    </div>
    <nav class="navbar">
     <?php include('partials/navbar.php')  ?>
    </nav>

<section class="hero">
    Become a VIP user today to enjoy premium tips
    	  <p>&nbsp;</p>
    	
</section>

<section class="register-section" id="register-vip">
    <h2>VIP Registration</h2>
    <p>Register now to access premium tips, track your predictions, and join our leaderboard!</p>
    <form action="" method="POST" style="display: flex; flex-direction: column; gap: 12px; max-width: 400px;">
        
        <input type="text" name="fullname" placeholder="Full Name" required 
               style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        
        <input type="email" name="email" placeholder="Email Address" required 
               style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        
        <input type="tel" name="phone" placeholder="Phone Number" required 
               style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        
          <input type="tel" name="phone" placeholder="Phone Number" required 
          style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        
        
        
        <input type="password" name="password" placeholder="Password" required minlength="6" 
               style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
      
      <select name="country" required
        style=" width: 105%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
      <?php foreach ($countries as $c): ?>
          <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
      <?php endforeach; ?>
        </select>
        <button type="submit" name="register_vip" 
                style="width: 106%;background: #007bff; color: #fff; padding: 10px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
            Register
        </button>
    </form>
</section>

<?php include('partials/whatsapp.php'); ?>
<footer>
    <?php include('partials/footer.php'); ?>
</footer>

  <!-- SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>
</body>
</html>
