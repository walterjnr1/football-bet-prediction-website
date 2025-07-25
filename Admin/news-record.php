<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Fetch news records
$stmt = $dbh->prepare("SELECT * FROM news ORDER BY published_at DESC");
$stmt->execute();
$newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>News Records - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
    <h2 class="mb-2">News Records</h2>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
      <div>
        <a href="upload-news" class="btn btn-success">
          <i class="bi bi-plus-circle me-1"></i> Add News
        </a>
      </div>
      <div class="input-group" style="max-width: 300px;">
        <input id="searchInput" type="search" class="form-control" placeholder="Search News..." />
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
      </div>
    </div>

    <div class="card shadow-sm p-3">
      <div class="table-responsive">
        <table id="newsTable" class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Content</th>
              <th>Image</th>
              <th>Published At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($newsList) > 0): ?>
            <?php foreach ($newsList as $index => $news): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($news['title']) ?></td>
                <td><?= htmlspecialchars($news['content']) ?></td>
                <td>
                  <?php if (!empty($news['image'])): ?>
                    <img src="../<?= htmlspecialchars($news['image']) ?>" alt="thumb" style="max-height: 50px;">
                  <?php else: ?>
                    <span class="text-muted">No image</span>
                  <?php endif; ?>
                </td>
                <td><?= date('Y-m-d H:i', strtotime($news['published_at'])) ?></td>
                <td>
                  <a href="edit-news.php?id=<?= $news['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                          data-id="<?= $news['id'] ?>" data-title="<?= htmlspecialchars($news['title']) ?>">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No news records found.</td>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the news titled:
        <strong id="newsTitleToDelete" class="text-dark"></strong>?
      </div>
      <div class="modal-footer">
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Yes, Delete</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const rowsPerPage = 10;
  const searchInput = document.getElementById("searchInput");
  const table = document.getElementById("newsTable").getElementsByTagName("tbody")[0];
  const pagination = document.getElementById("pagination");
  let currentPage = 1;
  let filteredRows = [];

  function updateTable() {
    const query = searchInput.value.toLowerCase();
    const allRows = Array.from(table.getElementsByTagName("tr"))
      .filter(row => !row.querySelector('td[colspan]')); // exclude "no records" row

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
      noDataRow.innerHTML = `<td colspan="6" class="text-center text-muted py-4">No records found.</td>`;
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

  // Modal delete setup
  const confirmModal = document.getElementById('confirmDeleteModal');
  confirmModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const newsId = button.getAttribute('data-id');
    const newsTitle = button.getAttribute('data-title');

    const deleteLink = document.getElementById('confirmDeleteBtn');
    const titleText = document.getElementById('newsTitleToDelete');

    deleteLink.href = `delete-news.php?id=${newsId}`;
    titleText.textContent = `"${newsTitle}"`;
  });
</script>

<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
