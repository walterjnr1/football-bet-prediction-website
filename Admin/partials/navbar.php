  <div class="container-fluid">
    <a class="navbar-brand" href="#">⚽ Admin Panel</a>
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="d-flex align-items-center ms-auto gap-3">
      <a class="nav-link" href="#"><i class="lni lni-cog"></i></a>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
             <?php
        $fullName = $row_user['full_name'];
        $initials = '';
        $parts = explode(' ', trim($fullName));
        if (count($parts) > 0 && strlen($parts[0]) > 0) {
          $initials = strtoupper($parts[0][0]);
        }
        //echo htmlspecialchars($initials);
        ?>
          <div id="admin-avatar"><?php echo $initials; ?></div>
          <span class="ms-2 fw-semibold"><?php echo $row_user['full_name']; ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="profile">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item text-danger" href="logout">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>