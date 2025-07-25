<?php
require_once('config.php');

session_start();
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        try {
            // First check if the email exists
            $stmt = $dbh->prepare("SELECT id, full_name, password_hash, role, status FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

              if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user['status'] !== 'active') {
                    $login_error = "Your account is not active.";
                } elseif (!password_verify($password, $user['password_hash'])) {
                    $login_error = "Incorrect password.";
                } else {
                    // All good, set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['full_name'];

                    // Log the login activity
         $action = "logged in as {$user['full_name']} on: $current_date"; 
          log_activity($dbh, $user['id'], $action, 'users',$user['id'], $ip_address);

                    // Redirect by role
                    if ($user['role'] === 'admin') {
                        header("Location: Admin/index");
                        exit();
                    } elseif ($user['role'] === 'vip') {
                        header("Location: vip/index");
                        exit();
                    } else {
                        $login_error = "Unauthorized role.";
                    }
                }
            } else {
                $login_error = "This email does not exist.";
            }
        } catch (PDOException $e) {
            $login_error = "Database error: " . $e->getMessage();
        }
    } else {
        $login_error = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Page - Football Prediction</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="shortcut icon" href="uploadImage/Logo/logo.png" type="image/x-icon" />
  <style>
    :root {
      --main-grey: #6c757d;
      --dark-grey: #343a40;
      --card-bg: #ffffff;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-container {
      max-width: 420px;
      width: 100%;
      padding: 30px;
      background-color: var(--card-bg);
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .login-container h3 {
      color: var(--dark-grey);
      margin-bottom: 25px;
    }

    .btn-login {
      background-color: var(--main-grey);
      color: white;
    }

    .btn-login:hover {
      background-color: var(--dark-grey);
    }

    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .logo i {
      font-size: 2rem;
      color: var(--main-grey);
      margin-right: 8px;
    }

    .logo span {
      font-size: 1.5rem;
      font-weight: bold;
      color: var(--dark-grey);
    }

    .footer-text {
      font-size: 0.85rem;
      color: #6c757d;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="logo">
    <i class="bi bi-shield-lock-fill"></i>
    <span>Login</span>
  </div>
  <h3 class="text-center">Welcome Back</h3>

  <form method="POST">
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label d-flex justify-content-between">
        <span>Password</span>
        <a href="#" class="text-decoration-none text-primary" style="font-size: 0.875rem;">Forgot?</a>
      </label>
      <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-login"><i class="bi bi-box-arrow-in-right"></i> Login</button>
    </div>
  </form>

  <div class="footer-text mt-4">
    <?php include('vip/partials/footer.php'); ?>
  </div>
</div>

<?php if (!empty($login_error)): ?>
<script>
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: <?= json_encode($login_error); ?>,
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    customClass: {
      popup: 'colored-toast'
    }
  });
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
