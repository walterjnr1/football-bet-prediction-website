<div class="container-fluid">
    <a class="navbar-brand" href="#">⚽ Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenuMobile">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="d-flex align-items-center gap-3 ms-auto">
      

      <div class="dropdown">
        <div class="dropdown-menu dropdown-menu-end shadow-lg p-3">
          <a class="dropdown-item" href="#"><i class="bi bi-key"></i> Change Password</a>
          <a class="dropdown-item" href="#"><i class="bi bi-pencil-square"></i> Edit Front Page</a>
          <a class="dropdown-item" href="#"><i class="bi bi-send"></i> Send VIP Message</a>
        </div>
      </div>

     <div class="d-flex align-items-center ms-auto gap-3">
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <div id="admin-avatar" style="background: #FFD600; color: #222; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
        <?php
        $fullName = $row_user['full_name'];
        $initials = '';
        $parts = explode(' ', trim($fullName));
        if (count($parts) > 0 && strlen($parts[0]) > 0) {
          $initials = strtoupper($parts[0][0]);
        }
        echo htmlspecialchars($initials);
        ?>
          </div>
          <span class="ms-2 fw-semibold"><?php echo htmlspecialchars($row_user['full_name']); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="profile">Profile</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item text-danger" href="logout">Logout</a></li>
        </ul>
      </div>
    </div>
    </div>
  </div>