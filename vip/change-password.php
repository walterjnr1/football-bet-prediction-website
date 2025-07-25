<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile - Victory Fixed</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

 <div class="navbar">
  <?php include 'partials/nav.php'; ?>
  </div>

  <div class="container">
    <div class="change-password-card">
      <h2>Change Password</h2>
      <form action="change_password.php" method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Update Password</button>
      </form>
    </div>
  </div>
  <script>
function toggleDropdown() {
      const dropdown = document.getElementById('userDropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Close dropdown or modal on outside click
    window.onclick = function(event) {
      const editModal = document.getElementById('editModal');
      const imageModal = document.getElementById('imageModal');
      const dropdown = document.getElementById('userDropdown');
      if (event.target == editModal) closeEditModal();
      if (event.target == imageModal) closeImageModal();
      if (!event.target.closest('.user-info')) dropdown.style.display = 'none';
    }
</script>
  <footer>
    &copy; 2025 Victory Fixed. All Rights Reserved.
  </footer>

</body>
</html>
