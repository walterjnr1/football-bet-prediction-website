<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - Victory Fixed</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
  
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
      <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="tel" name="phone" placeholder="Phone Number (optional)">
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit"><i class="fas fa-paper-plane"></i> Send Message</button>
    </form>

    <div class="contact-info">
      <p>Email: support@victoryfixed.com</p>
      <p>Phone: +234 801 234 5678</p>
      <p>Address: 12 Unity Lane, Lagos, Nigeria</p>
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
  <!-- FOOTER -->
  <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

</body>
</html>
