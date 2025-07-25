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

  <!-- NAVBAR -->
  <div class="navbar">
  <?php include 'partials/nav.php'; ?>
  </div>

  <!-- PROFILE CARD -->
  <div class="container">
    <div class="profile-card">
      <div class="profile-img-container">
        <img src="../images/person_2.jpg" alt="User Image" class="profile-img" id="profileImage">
        <div class="edit-photo-btn" onclick="openImageModal()"><i class="fas fa-edit"></i></div>
      </div>
      <div class="profile-details">
        <h2>Harry Joe</h2>
        <p>Email: harryjoe@example.com</p>
        <p>Phone: +234 800 123 4567</p>
        <p>Joined: Jan 2024</p>
      </div>
      <button class="edit-btn" onclick="openEditModal()">Edit Profile</button>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div class="modal" id="editModal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeEditModal()">&times;</span>
      <h3>Edit Profile</h3>
      <input type="text" placeholder="Full Name" value="Harry Joe">
      <input type="email" placeholder="Email" value="harryjoe@example.com">
      <input type="tel" placeholder="Phone" value="+234 800 123 4567">
      <button>Save Changes</button>
    </div>
  </div>

  <!-- Image Upload Modal -->
  <div class="modal-img" id="imageModal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeImageModal()">&times;</span>
      <h3>Change Profile Picture</h3>
      <input type="file" id="imageInput" accept="image/*" onchange="previewImage(event)">
      <img id="preview-img" src="" alt="Image Preview">
      <button>Upload</button>
    </div>
  </div>

  <footer>
        <?php include 'partials/footer.php'; ?>
  </footer>

  <!-- Script Section -->
  <script>
    function openEditModal() {
      document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    function openImageModal() {
      document.getElementById('imageModal').style.display = 'block';
    }

    function closeImageModal() {
      document.getElementById('imageModal').style.display = 'none';
    }

    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function(){
        const output = document.getElementById('preview-img');
        output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }

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

</body>
</html>
