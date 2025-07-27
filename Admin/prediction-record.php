<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$query = "
    SELECT p.*,th.name AS team_home,ta.name AS team_away,l.name AS league_name,l.country AS league_country    FROM predictions p    LEFT JOIN teams th ON p.team_home_id = th.id
    LEFT JOIN teams ta ON p.team_away_id = ta.id    LEFT JOIN leagues l ON p.league_id = l.id    ORDER BY p.match_date DESC
";
$stmt = $dbh->prepare($query);
$stmt->execute();
$row_predictions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Prediction Records - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3">
  <?php include 'partials/nav.php'; ?>
</nav>

<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenuMobile">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-2">Prediction Records</h2>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
      <a href="add-predictions" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i> Add Prediction
      </a>
      <div class="input-group" style="max-width: 300px;">
        <input id="searchInput" type="search" class="form-control" placeholder="Search Predictions..." />
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
      </div>
    </div>

    <div class="card shadow-sm p-3">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>League</th>
              <th>Match</th>
              <th>Prediction</th>
              <th>Odds</th>
              <th>Result</th>
              <th>Score</th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($row_predictions): ?>
            <?php foreach ($row_predictions as $index => $pred): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= date('Y-m-d', strtotime($pred['match_date'])) ?></td>
                <td><?= htmlspecialchars($pred['league_name']) ?> (<?= htmlspecialchars($pred['league_country']) ?>)</td>
                <td><?= htmlspecialchars($pred['team_home']) ?> vs <?= htmlspecialchars($pred['team_away']) ?></td>
                <td><?= htmlspecialchars($pred['prediction_text']) ?></td>
                <td><?= htmlspecialchars($pred['odds']) ?></td>
                <td>
                  <?php
                    echo match($pred['result']) {
                      'won' => '<span class="badge bg-success">Won</span>',
                      'lose' => '<span class="badge bg-danger">Lose</span>',
                      default => '<span class="badge bg-secondary">Pending</span>'
                    };
                  ?>
                </td>
                <td><?= htmlspecialchars($pred['score'] ?? '-') ?></td>
                <td><?= ucfirst($pred['type']) ?></td>
                <td>
                  <button 
                    class="btn btn-sm btn-info updateBtn"
                    data-bs-toggle="modal" 
                    data-bs-target="#updateModal"
                    data-id="<?= $pred['id'] ?>"
                    data-result="<?= $pred['result'] ?>"
                    data-score="<?= $pred['score'] ?>">
                    <i class="bi bi-clipboard-check"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="10" class="text-center text-muted py-4">No prediction records found.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="update-prediction-outcome.php" class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="updateModalLabel">Update Prediction Outcome</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="prediction_id" id="predictionId">
        <div class="mb-3">
          <label for="result" class="form-label">Result</label>
          <select name="result" id="result" class="form-select" required>
            <option value="pending">Pending</option>
            <option value="won">Won</option>
            <option value="lose">Lose</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="score" class="form-label">Score</label>
          <input type="text" name="score" id="score" class="form-control" placeholder="e.g., 2-1">
        </div>
        <div class="mb-3">
          <label for="notes" class="form-label">Notes (for VIP Results)</label>
          <textarea name="notes" id="notes" class="form-control" placeholder="Optional notes..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const updateModal = document.getElementById('updateModal');
  updateModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const predictionId = button.getAttribute('data-id');
    const result = button.getAttribute('data-result');
    const score = button.getAttribute('data-score');

    document.getElementById('predictionId').value = predictionId;
    document.getElementById('result').value = result;
    document.getElementById('score').value = score;
  });
</script>

<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
