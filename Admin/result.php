<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Result Management - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root {
      --main-grey: #6c757d;
      --dark-grey: #343a40;
      --light-grey: #e9ecef;
      --card-bg: #ffffff;
      --info-blue: #0dcaf0;
      --warning-yellow: #ffc107;
      --danger-red: #dc3545;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      color: #333;
    }

    .sidebar {
      background-color: var(--dark-grey);
      color: #fff;
      height: 100vh;
      padding-top: 70px;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      font-size: 0.95rem;
      transition: 0.3s;
    }

    .sidebar a:hover {
      background-color: var(--main-grey);
    }

    .sidebar .submenu a {
      padding-left: 40px;
    }

    .navbar {
      background-color: var(--main-grey);
      z-index: 1050;
    }

    .navbar .nav-link {
      color: white !important;
      margin-right: 20px;
    }

    .navbar .dropdown-menu {
      right: 0;
      left: auto;
      min-width: 300px;
    }

    .navbar #navbar-image img {
      border: 2px solid #fff;
      transition: 0.3s ease-in-out;
    }

    .navbar #navbar-image img:hover {
      transform: scale(1.05);
      border-color: var(--warning-yellow);
    }

    footer {
      background-color: var(--dark-grey);
      color: white;
      text-align: center;
      padding: 15px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      z-index: 999;
    }

    @media (min-width: 992px) {
      .desktop-sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 220px;
        z-index: 1030;
      }

      main {
        margin-left: 220px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">⚽ Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="d-flex align-items-center gap-3 position-relative ms-auto">
      <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" data-bs-toggle="dropdown">
          <i class="bi bi-envelope"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow-lg p-3">
          <h6 class="dropdown-header text-success">📬 Unread Messages</h6>
          <div class="mb-2">
            <div class="d-flex justify-content-between">
              <small><strong>VIP User</strong></small>
              <small class="text-muted">2h ago</small>
            </div>
            <p class="mb-1 text-dark">Please send today's VIP games...</p>
            <hr />
          </div>
          <h6 class="dropdown-header text-secondary">✅ Read Messages</h6>
          <div>
            <div class="d-flex justify-content-between">
              <small><strong>Member</strong></small>
              <small class="text-muted">1d ago</small>
            </div>
            <p class="mb-1 text-muted">Thanks for the predictions!</p>
          </div>
        </div>
      </div>

      <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" data-bs-toggle="dropdown">
          <i class="bi bi-gear"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow-lg p-3">
          <a class="dropdown-item" href="#"><i class="bi bi-key"></i> Change Password</a>
          <a class="dropdown-item" href="#"><i class="bi bi-pencil-square"></i> Edit Front Page</a>
          <a class="dropdown-item" href="#"><i class="bi bi-send"></i> Send VIP Message</a>
        </div>
      </div>

      <div class="dropdown" id="navbar-image">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="adminDropdown" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/40" alt="Admin" class="rounded-circle me-2">
          <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Sidebar (Desktop) -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
  <a href="#"><i class="bi bi-house"></i> Home</a>
  <a data-bs-toggle="collapse" href="#predictionsMenuDesktop" role="button"><i class="bi bi-trophy"></i> Predictions</a>
  <div class="collapse submenu" id="predictionsMenuDesktop">
    <a href="#">Free Games</a>
    <a href="#">Fixed Games</a>
  </div>
  <a href="#"><i class="bi bi-ticket"></i> Ticket Proof</a>
  <a href="#"><i class="bi bi-graph-up"></i> VIP Result Management</a>
  <a href="#"><i class="bi bi-person-circle"></i> VIP Profiles</a>
  <a href="#"><i class="bi bi-pencil-square"></i> Edit Front Page</a>
  <a href="#"><i class="bi bi-send"></i> Send VIP Message</a>
  <a href="#"><i class="bi bi-key"></i> Change Password</a>
  <a href="#"><i class="bi bi-globe2"></i> Website Management</a>
  <a href="#"><i class="bi bi-box-arrow-right text-danger"></i> Logout</a>
</div>

<!-- Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">⚽ Admin Menu</h5>
    <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body px-0">
    <a href="#"><i class="bi bi-house"></i> Home</a>
    <a data-bs-toggle="collapse" href="#predictionsMenu" role="button"><i class="bi bi-trophy"></i> Predictions</a>
    <div class="collapse submenu" id="predictionsMenu">
      <a href="#">Free Games</a>
      <a href="#">Fixed Games</a>
    </div>
    <a href="#"><i class="bi bi-ticket"></i> Ticket Proof</a>
    <a href="#"><i class="bi bi-graph-up"></i> VIP Result Management</a>
    <a href="#"><i class="bi bi-person-circle"></i> VIP Profiles</a>
    <a href="#"><i class="bi bi-pencil-square"></i> Edit Front Page</a>
    <a href="#"><i class="bi bi-send"></i> Send VIP Message</a>
    <a href="#"><i class="bi bi-key"></i> Change Password</a>
    <a href="#"><i class="bi bi-globe2"></i> Website Management</a>
    <a href="#"><i class="bi bi-box-arrow-right text-danger"></i> Logout</a>
  </div>
</div>

<!-- Main Content -->
<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4">Upload Prediction Result</h2>
    <div class="card shadow-sm p-4">
      <form>
        <div class="mb-3">
          <label for="predictionType" class="form-label">Prediction Type</label>
          <select class="form-select" id="predictionType" required>
            <option value="" selected disabled>Select type</option>
            <option value="Free">Free Game</option>
            <option value="Fixed">Fixed Game</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="matchDate" class="form-label">Match Date</label>
          <input type="date" class="form-control" id="matchDate" required>
        </div>
        <div class="mb-3">
          <label for="matchInfo" class="form-label">Match Info</label>
          <input type="text" class="form-control" id="matchInfo" placeholder="e.g. Team A vs Team B" required>
        </div>
        <div class="mb-3">
          <label for="prediction" class="form-label">Prediction</label>
          <input type="text" class="form-control" id="prediction" placeholder="e.g. Over 2.5, 1X2, etc." required>
        </div>
        <div class="mb-3">
          <label for="result" class="form-label">Result</label>
          <input type="text" class="form-control" id="result" placeholder="e.g. 3-1" required>
        </div>
        <div class="mb-3">
          <label for="ticketStatus" class="form-label">Ticket Status</label>
          <select class="form-select" id="ticketStatus" required>
            <option value="" selected disabled>Select ticket status</option>
            <option value="Won">Won</option>
            <option value="Lost">Lost</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-upload"></i> Submit Result</button>
      </form>
    </div>
  </div>
  <p>&nbsp;</p>
</main>

<!-- Footer -->
<footer>
  <div class="container">
    &copy; <script>document.write(new Date().getFullYear());</script> Football Prediction Admin Panel. All Rights Reserved.
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
