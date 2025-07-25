<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 Not Found - Victory Fixed</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>

</head>
<body>

  <!-- NAVBAR -->
  <div class="navbar">
    <?php include 'partials/nav.php'; ?>
  </div>

  <!-- 404 ERROR CONTENT -->
  <div class="error-container">
    <h1><i class="fas fa-exclamation-triangle"></i> 404</h1>
    <h2>Page Not Found</h2>
    <p>Sorry, the page you’re looking for doesn’t exist or has been moved.</p>
    <a href="index.php"><i class="fas fa-home"></i> Back to Homepage</a>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('userDropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    window.onclick = function(event) {
      const dropdown = document.getElementById('userDropdown');
      if (!event.target.closest('.user-info')) dropdown.style.display = 'none';
    }
  </script>

  <!-- FOOTER -->
  <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

</body>
</html>
