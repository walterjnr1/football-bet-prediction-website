<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Fetch all leagues for the dropdown
$leaguesStmt = $dbh->query("SELECT id, name, country FROM leagues ORDER BY name ASC");
$leagues = $leaguesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_team'])) {
    $name = trim($_POST['name'] ?? '');
    $league_id = intval($_POST['league'] ?? 0);

    if ($name === '' || $league_id <= 0) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Team name and League are required.'];
        header("Location: add-team");
        exit;
    }

    // Check if team already exists in the league
    $stmt = $dbh->prepare("SELECT * FROM teams WHERE name = ? AND league_id = ?");
    $stmt->execute([$name, $league_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'This team already exists in the selected league.'];
        header("Location: add-team");
        exit;
    }

    // Insert new team
    $stmt = $dbh->prepare("INSERT INTO teams (name, league_id) VALUES (?, ?)");
    $stmt->execute([$name, $league_id]);
    $team_id = $dbh->lastInsertId();

    // Get league name and country for logging
    $league_info = '';
    foreach ($leagues as $league) {
        if ($league['id'] == $league_id) {
            $league_info = $league['name'] . ' (' . $league['country'] . ')';
            break;
        }
    }

    $current_date = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $action = "Added new team: $name to $league_info on $current_date";
    log_activity($dbh, $_SESSION['user_id'], $action, 'teams', $team_id, $ip_address);

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Team added successfully.'];
    header("Location: add-team");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Team - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
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
        <h2 class="mb-4">Add New Team</h2>
        <div class="card shadow-sm p-4">
            <form method="POST">
                <input type="hidden" name="add_team" value="1">

                <div class="mb-3">
                    <label class="form-label">Team Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Arsenal" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select League</label>
                    <select name="league" class="form-select" required>
                        <option value="">-- Select League --</option>
                        <?php foreach ($leagues as $league): ?>
                            <option value="<?= $league['id'] ?>">
                                <?= htmlspecialchars($league['name']) ?> (<?= htmlspecialchars($league['country']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Add Team</button>
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
