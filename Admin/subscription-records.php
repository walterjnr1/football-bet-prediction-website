<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Fetch subscriptions with user, plan info and latest expiry date per user
$stmt = $dbh->prepare("
    SELECT 
        s.id, 
        u.id AS user_id, 
        u.full_name, 
        p.name AS plan_name, 
        p.amount, 
        s.start_date, 
        s.end_date,
        (
            SELECT MAX(s2.end_date) 
            FROM subscriptions s2 
            WHERE s2.user_id = s.user_id
        ) AS latest_expiry
    FROM subscriptions s
    JOIN users u ON s.user_id = u.id
    JOIN plans p ON s.plan_id = p.id
    ORDER BY s.start_date DESC
");
$stmt->execute();
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Keep track of user_ids already shown (to prevent duplicate expiry badges)
$shown_users = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Subscription Records - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3">
  <?php include 'partials/nav.php'; ?>
</nav>

<!-- Sidebar -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenuMobile">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-2">Subscription Records</h2>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
      <div>
        <a href="add-subscription" class="btn btn-success">
          <i class="bi bi-plus-circle me-1"></i> Add Subscription
        </a>
      </div>
      <div class="input-group" style="max-width: 300px;">
        <input id="searchInput" type="search" class="form-control" placeholder="Search subscriptions..." />
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
      </div>
    </div>

    <div class="card shadow-sm p-3">
      <div class="table-responsive">
        <table id="subscriptionTable" class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Plan</th>
              <th>Amount</th>
              <th>Start Date</th>
              <th>End Date</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($subscriptions) > 0): ?>
            <?php foreach ($subscriptions as $index => $sub): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td>
                  <?= htmlspecialchars($sub['full_name']) ?>
                  <?php if (!in_array($sub['user_id'], $shown_users)): ?>
                    <span class="badge bg-danger ms-2">Expiry date: <?= date('Y-m-d', strtotime($sub['latest_expiry'])) ?></span>
                    <?php $shown_users[] = $sub['user_id']; ?>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($sub['plan_name']) ?></td>
                <td>N<?= number_format($sub['amount'], 2) ?></td>
                <td><?= date('Y-m-d', strtotime($sub['start_date'])) ?></td>
                <td><?= date('Y-m-d', strtotime($sub['end_date'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No subscriptions found.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <nav aria-label="Page navigation" class="d-flex justify-content-end">
        <ul id="pagination" class="pagination pagination-sm mt-3 mb-0"></ul>
      </nav>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container">
    &copy; <script>document.write(new Date().getFullYear());</script> Football Prediction Admin Panel. All Rights Reserved.
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const rowsPerPage = 10;
  const searchInput = document.getElementById("searchInput");
  const table = document.getElementById("subscriptionTable").getElementsByTagName("tbody")[0];
  const pagination = document.getElementById("pagination");
  let currentPage = 1;
  let filteredRows = [];

  function updateTable() {
    const query = searchInput.value.toLowerCase();
    const allRows = Array.from(table.getElementsByTagName("tr"))
      .filter(row => !row.querySelector('td[colspan]'));

    filteredRows = allRows.filter(row => row.innerText.toLowerCase().includes(query));
    renderTable();
    renderPagination();
  }

  function renderTable() {
    Array.from(table.getElementsByTagName("tr")).forEach(row => {
      row.style.display = "none";
    });

    if (filteredRows.length === 0) {
      const noDataRow = document.createElement("tr");
      noDataRow.innerHTML = `<td colspan="6" class="text-center text-muted py-4">No subscriptions found.</td>`;
      table.innerHTML = '';
      table.appendChild(noDataRow);
      pagination.innerHTML = '';
      return;
    }

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    filteredRows.slice(start, end).forEach(row => row.style.display = "");
  }

  function renderPagination() {
    pagination.innerHTML = "";
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === currentPage ? "active" : ""}`;
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage = i;
        renderTable();
        renderPagination();
      });
      pagination.appendChild(li);
    }
  }

  searchInput.addEventListener("input", () => {
    currentPage = 1;
    updateTable();
  });

  updateTable();
</script>

<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
