    <div class="nav-left">
      <div class="nav-logo">
        <a href="#"><img src="../<?php echo $app_logo; ?>" alt="Logo"></a>
      </div>
      <div class="nav-links">
        <a href="index">Home</a>
        <a href="#">Live Score</a>
        <a href="contact-us">Contact Us</a>
      </div>
    </div>

    <div class="user-info" onclick="toggleDropdown()">
      <img src="../<?php echo $app_logo; ?>" alt="User" id="navProfileImg">
      <span id="navUserName"><?php echo $row_user['full_name']; ?></span>
      <div class="dropdown" id="userDropdown">
        <a href="profile">Profile</a>
        <a href="logout">Logout</a>
      </div>
    </div>
