<div class="logo-area">
            <img src="../<?php echo $app_logo; ?>" alt="Victory Fixed Logo">
            <span><?php echo $app_name; ?></span>
        </div>
        <div class="nav-links">
            <a href="index">Home</a>
            <a href="livescores" target="_blank">Live Score</a>
            <a href="contact-us">Contact Us</a>
             <a href="logout">Logout</a>
        </div>
        <div class="user-area" style="position: relative;">
            <span class="user-name" id="userDropdownToggle"><?php echo $row_user['full_name']; ?></span>
            <div class="dropdown" id="userDropdown" style="display: inline-flex; align-items: center; cursor: pointer;">
                <img src="../<?php echo $row_user['image']; ?>" alt="User" class="user-img" id="userImgDropdown" style="width: 32px; height: 32px; border-radius: 50%;">
                <i class="fas fa-chevron-down" id="dropdownIcon" style="color:#fff; font-size:15px; margin-left:4px;"></i>
            </div>
            <!-- Dropdown menu -->
            <div class="dropdown-content" id="dropdownMenu">
                <a href="profile"><i class="fas fa-user"></i> Profile</a>
                <a href="change-password"><i class="fas fa-key"></i> Change Password</a>
                <a href="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>