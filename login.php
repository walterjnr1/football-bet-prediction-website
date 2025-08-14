<?php
include('config.php');
include('inc/email_landing_page.php'); // Optional, if needed for login notifications

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_vip'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Email and password are required.'];
        header("Location: login");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid email format.'];
        header("Location: login");
        exit;
    }

    // Check if user exists
    $stmt = $dbh->prepare("SELECT id, full_name, email, password_hash FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        // Log activity
        $action = "Logged in as VIP on: " . date("Y-m-d H:i:s");
        log_activity($dbh, $user['id'], $action, 'users', $user['id'], $_SERVER['REMOTE_ADDR']);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Login successful! Welcome back, ' . htmlspecialchars($user['full_name']) . '.'];
        header("Location: vip/index");
        exit;
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid email or password.'];
        header("Location: login");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>VIP Login - <?php echo $app_name; ?></title>
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
    Welcome back! Please log in to access your VIP dashboard.
    	  <p>&nbsp;</p>
    	    	  <p>&nbsp;</p>

</section>

<section class="register-section" id="register-vip">
    <h2>VIP Login</h2>
    <p>Enter your credentials to access premium tips, track predictions, and join the leaderboard.</p>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required minlength="6">
        <button type="submit" name="login_vip">Login</button>
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
