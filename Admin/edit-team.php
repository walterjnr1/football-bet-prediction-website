<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$team_id = intval($_GET['id'] ?? 0);
if ($team_id <= 0) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid team ID.'];
    header("Location: teams");
    exit;
}

// Fetch team details
$stmt = $dbh->prepare("SELECT * FROM teams WHERE id = ?");
$stmt->execute([$team_id]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$team) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Team not found.'];
    header("Location: teams");
    exit;
}

// Fetch all leagues for dropdown
$leaguesStmt = $dbh->query("SELECT id, name, country FROM leagues ORDER BY name ASC");
$leagues = $leaguesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_team'])) {
    $name = trim($_POST['name'] ?? '');
    $league_id = intval($_POST['league'] ?? 0);

    if ($name === '' || $league_id <= 0) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Team name and League are required.'];
        header("Location: edit-team?id=$team_id");
        exit;
    }

    // Check for duplicate in same league (excluding current team)
    $check = $dbh->prepare("SELECT * FROM teams WHERE name = ? AND league_id = ? AND id != ?");
    $check->execute([$name, $league_id, $team_id]);
    if ($check->fetch()) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Another team with this name exists in the selected league.'];
        header("Location: edit-team?id=$team_id");
        exit;
    }

    // Update team
    $update = $dbh->prepare("UPDATE teams SET name = ?, league_id = ? WHERE id = ?");
    $update->execute([$name, $league_id, $team_id]);

    // Get league name and country
    $league_info = '';
    foreach ($leagues as $league) {
        if ($league['id'] == $league_id) {
            $league_info = $league['name'] . ' (' . $league['country'] . ')';
            break;
        }
    }

    // Log
    $current_date = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $action = "Updated team: $name to $league_info on $current_date";
    log_activity($dbh, $_SESSION['user_id'], $action, 'teams', $team_id, $ip_address);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Team updated successfully.'];
    header("Location: team-record?id=$team_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Team - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
    <?php include 'partials/head.php'; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
    <?php include 'partials/nav.php'; ?>
</nav>

<div class="d-none d-lg-block sidebar desktop-sidebar">
    <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
    <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<main class="pt-5 mt-4 mb-5">
    <div class="container mt-4">
        <h2 class="mb-4">Edit Team</h2>
        <div class="card shadow-sm p-4">
            <form method="POST">
                <input type="hidden" name="update_team" value="1">

                <div class="mb-3">
                    <label class="form-label">Team Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($team['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select League</label>
                    <select name="league" class="form-select" required>
                        <option value="">-- Select League --</option>
                        <?php foreach ($leagues as $league): ?>
                            <option value="<?= $league['id'] ?>" <?= $league['id'] == $team['league_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($league['name']) ?> (<?= htmlspecialchars($league['country']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Team</button>
            </form>
        </div>
    </div>
</main>

<footer>
    <div class="container">
        <?php include '../vip/partials/footer.php'; ?>
    </div>
</footer>

<?php include 'partials/sweetalert.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
