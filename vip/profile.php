<?php
include('../inc/config.php');
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $dbh->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$row_user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $image = $_FILES['profile_image'];
    $upload_dir = '../uploadImage/Profile/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (in_array($image['type'], $allowed_types) && $image['size'] <= $max_size) {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $new_filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $upload_path = $upload_dir . $new_filename;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($image['tmp_name'], $upload_path)) {
            $relative_path = str_replace('../', '', $upload_path);
            $stmt = $dbh->prepare("UPDATE users SET image = ? WHERE id = ?");
            $stmt->execute([$relative_path, $user_id]);

            //  activity log
         $action = "Upload profile image on: $current_date"; 
          log_activity($dbh, $user_id, $action, 'users',$row_user['id'], $ip_address);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile image updated successfully!'];
            header("Location: profile");
            exit;
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to upload image.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid image type or size exceeds 2MB.'];
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!empty($full_name) && !empty($email) && !empty($phone)) {
        $stmt = $dbh->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$full_name, $email, $phone, $user_id]);

          //  activity log
         $action = "Upload profile data on: $current_date"; 
          log_activity($dbh, $user_id, $action, 'users',$row_user['id'], $ip_address);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile updated successfully!'];
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'All fields are required.'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile - Victory Fixed</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/profile.css" />
  <link rel="shortcut icon" href="../<?php echo $app_logo; ?>" type="image/x-icon" />

</head>
<body>

<div class="navbar">
  <?php include 'partials/nav.php'; ?>
</div>

<div class="container">
  <div class="profile-card">
    <div class="profile-img-container">
      <img src="../<?php echo htmlspecialchars($row_user['image']); ?>" alt="User Image" class="profile-img" id="profileImage">
      <div class="edit-photo-btn" onClick="openImageModal()"><i class="fas fa-edit"></i></div>
    </div>
    <div class="profile-details">
      <h2><?php echo htmlspecialchars($row_user['full_name']); ?></h2>
      <p><span class="style1">Email:</span> <?php echo htmlspecialchars($row_user['email']); ?></p>
      <p><span class="style1">Phone:</span> <?php echo htmlspecialchars($row_user['phone']); ?></p>
      <p><span class="style1">Joined:</span> <?php echo date("F j, Y", strtotime($row_user['created_at'])); ?></p>
    </div>
    <button class="edit-btn" onClick="openEditModal()">Edit Profile</button>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal" id="editModal">
  <div class="modal-content">
    <span class="close-btn" onClick="closeEditModal()">&times;</span>
    <h3>Edit Profile</h3>
    <form method="POST" action="profile.php">
      <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($row_user['full_name']); ?>" required />
      <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($row_user['email']); ?>" required />
      <input type="text" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($row_user['phone']); ?>" required />
      <button type="submit" name="update_profile">Save Changes</button>
    </form>
  </div>
</div>

<!-- Image Upload Modal -->
<div class="modal" id="imageModal">
  <div class="modal-content">
    <span class="close-btn" onClick="closeImageModal()">&times;</span>
    <h3>Change Profile Picture</h3>
    <form action="profile.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="profile_image" accept="image/*" required onChange="previewImage(event)" />
      <img id="preview-img" src="../<?php echo htmlspecialchars($row_user['image']); ?>" alt="Preview" />
      <button type="submit">Upload Image</button>
    </form>
  </div>
</div>

<footer>
  <?php include 'partials/footer.php'; ?>
</footer>

<script>
  function openEditModal() {
    document.getElementById("editModal").style.display = "block";
  }

  function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
  }

  function openImageModal() {
    document.getElementById("imageModal").style.display = "block";
  }

  function closeImageModal() {
    document.getElementById("imageModal").style.display = "none";
  }

  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const output = document.getElementById('preview-img');
      output.src = reader.result;
      output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
   

    <!-- ✅ SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>
</body>
</html>
