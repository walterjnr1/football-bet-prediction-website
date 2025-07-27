<?php
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit();
}
$user_id = $_SESSION['user_id'];

$leagues = $dbh->query("SELECT id, name, country FROM leagues ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$teams = $dbh->query("SELECT id, name FROM teams ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$tips = $dbh->query("SELECT name FROM tips ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'free';
    $sport = $_POST['sport'] ?? '';
    $match_date = $_POST['match_date'] ?? '';
    $league_id = $_POST['league_id'] ?? '';
    $league_other = trim($_POST['league_other'] ?? '');
    $home_id = $_POST['team_home_id'] ?? '';
    $home_other = trim($_POST['team_home_other'] ?? '');
    $away_id = $_POST['away_home_id'] ?? '';
    $away_other = trim($_POST['team_away_other'] ?? '');
    $prediction_text = $_POST['prediction_text'] ?? '';
    $tip_other = trim($_POST['tip_other'] ?? '');
    $odds = $_POST['odds'] ?? '';

    if ($league_id === 'other' && $league_other) {
        $dbh->prepare("INSERT INTO leagues (name, country) VALUES (?, '')")->execute([$league_other]);
        $league_id = $dbh->lastInsertId();
    }

    if ($home_id === 'other' && $home_other) {
        $dbh->prepare("INSERT INTO teams (name, league_id) VALUES (?, ?)")
            ->execute([$home_other, $league_id]);
        $home_id = $dbh->lastInsertId();
    }

    if ($away_id === 'other' && $away_other) {
        $dbh->prepare("INSERT INTO teams (name, league_id) VALUES (?, ?)")
            ->execute([$away_other, $league_id]);
        $away_id = $dbh->lastInsertId();
    }

    if (!$home_id || !$away_id) {
        $_SESSION['toast'] = ['type'=>'error','message'=>'Invalid team selections.'];
        header("Location:add-predictions"); exit();
    }

    if ($prediction_text === 'other' && $tip_other) {
        $prediction_text = $tip_other;
        $dbh->prepare("INSERT INTO tips (name, description) VALUES (?, '')")->execute([$tip_other]);
    }

    $dbh->prepare("
        INSERT INTO predictions
          (type, sport, match_date, league_id, team_home_id, team_away_id, prediction_text, odds, result, score)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NULL)
    ")->execute([$type, $sport, $match_date, $league_id, $home_id, $away_id, $prediction_text, $odds]);

    $prediction_id = $dbh->lastInsertId();
    $home_team = getTeamName($dbh, $home_id);
    $away_team = getTeamName($dbh, $away_id);
    $league_name = getLeagueName($dbh, $league_id);
    $today = date('Y-m-d');

    $stmtVip = $dbh->prepare("
      SELECT u.full_name, u.email
      FROM users u
      JOIN subscriptions s ON u.id = s.user_id
      WHERE u.role='vip' AND s.end_date >= ?
    ");
    $stmtVip->execute([$today]);
    $vip_users = $stmtVip->fetchAll(PDO::FETCH_ASSOC);

    foreach ($vip_users as $vip) {
        $first_name = explode(' ', trim($vip['full_name']))[0];
        $email = $vip['email'];
        $subject = "New Prediction Available: $home_team vs $away_team";

        $message = '
        <html><head><style>
            body { font-family: "Segoe UI", Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
            .container { max-width:600px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 0 14px rgba(0,0,0,0.06); }
            h2 { color:#2d3748; font-size:24px; margin-bottom:20px; }
            p { color:#4a5568; font-size:16px; line-height:1.7; }
            .highlight { background:#edf2f7; padding:18px; border-radius:10px; margin:24px 0; }
            .highlight strong { display:block; margin-bottom:8px; color:#2b6cb0; font-size:16px; }
            .sport-tag { background:#2b6cb0; color:#fff; padding:10px 18px; font-size:18px; font-weight:bold; text-align:center; border-radius:6px; margin-bottom:20px; }
            .cta { display:inline-block; margin-top:28px; background:#38a169; color:#fff; text-decoration:none; padding:14px 24px; border-radius:8px; font-weight:bold; font-size:16px; }
            .footer { text-align:center; margin-top:30px; color:#aaa; font-size:14px; border-top:1px solid #e2e8f0; padding-top:16px; }
        </style></head><body>
        <div class="container">
            <h2>Hi '.htmlspecialchars($first_name).',</h2>
            <p>We’ve just analyzed today’s top match and here’s a fresh betting prediction from our expert team:</p>
            <div class="sport-tag">'.strtoupper(htmlspecialchars($sport)).'</div>
            <div class="highlight">
              <strong>Match:</strong> '.htmlspecialchars($home_team).' vs '.htmlspecialchars($away_team).'<br>
                <strong>League:</strong> '.htmlspecialchars($league_name).'<br>
                <strong>Match Date/Time:</strong> '.htmlspecialchars(date('F j, Y h:i A', strtotime($match_date))).'<br>
              <strong>Prediction Type:</strong> '.strtoupper(htmlspecialchars($type)).'<br>
              <strong>Tip:</strong> '.htmlspecialchars($prediction_text).'<br>
              <strong>Odds:</strong> '.htmlspecialchars($odds).'
            </div>
            <p>This prediction is based on current form, head‑to‑head stats, injury reports, and strategic analysis from our betting experts.</p>
            <p><strong>Note:</strong> Bet responsibly. Only stake what you can afford to lose. This prediction is a recommendation, not a guarantee.</p>
            <a href="'.htmlspecialchars($app_url.'/Admin').'" class="cta">View More Predictions</a>
            <div class="footer">&copy; '.date('Y').' '.htmlspecialchars($app_name ?? 'Victory Fixed').'. All rights reserved.</div>
        </div></body></html>';

        sendEmail($email, $subject, $message);
    }

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    log_activity($dbh, $user_id, "Posted new prediction", 'predictions', $prediction_id, $ip_address);

    $_SESSION['toast'] = ['type'=>'success','message'=>'Prediction posted and email sent to active VIP users.'];
    header("Location:add-predictions");
    exit();
}

function getTeamName($dbh, $id) {
    $stmt = $dbh->prepare("SELECT name FROM teams WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: '';
}

function getLeagueName($dbh, $id) {
    $stmt = $dbh->prepare("SELECT name FROM leagues WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: '';
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>New Predictions - <?= htmlspecialchars($app_name ?? 'Dashboard') ?></title>
  <?php include 'partials/head.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark px-3 fixed-top">
  <?php include 'partials/nav.php'; ?>
</nav>

<!-- Sidebar (Desktop) -->
<div class="d-none d-lg-block sidebar desktop-sidebar">
  <?php include 'partials/desktop-sidebar.php'; ?>
</div>

<!-- Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start sidebar text-white" tabindex="-1" id="sidebarMenu">
  <?php include 'partials/mobile-sidebar.php'; ?>
</div>

<!-- Main Content -->
<main class="pt-5 mt-4 mb-5">
  <div class="container mt-4">
    <h2 class="mb-4">Post Predictions</h2>
    <div class="card shadow-sm p-4">
     <form method="POST">
  <div class="mb-3">
    <label class="form-label">Prediction Type</label>
    <select name="type" class="form-select" required>
      <option value="free">Free</option>
      <option value="fixed">Fixed</option>
    </select>
  </div>
<div class="mb-3">
    <label class="form-label">Type Of Sport</label>
    <select name="sport" class="form-select" required>
      <option value="football">Football</option>
      <option value="basketball">Basketball</option>
      <option value="tennis">Tennis</option>
      <option value="baseball">Baseball</option>
      <option value="hockey">Hockey</option>
      <option value="cricket">Cricket</option>
      <option value="rugby">Rugby</option>
      <option value="boxing">Boxing</option>
      <option value="mma">MMA</option>
      <option value="esports">Esports</option>
      <option value="Wrestling">Wrestling</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Match Date</label>
    <input type="datetime-local" class="form-control" name="match_date" required>
  </div>

  <div class="mb-3">
    <label class="form-label">League</label>
    <select name="league_id" class="form-select" onchange="toggleOther(this, 'league_other')" required>
      <?php foreach ($leagues as $l): ?>
        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['name'] . " ({$l['country']})") ?></option>
      <?php endforeach; ?>
      <option value="other">-- Other --</option>
    </select>
    <input type="text" class="form-control mt-2 d-none" name="league_other" id="league_other" placeholder="Enter new league">
  </div>

  <div class="mb-3">
    <label class="form-label">Home Team</label>
    <select name="team_home_id" class="form-select" onchange="toggleOther(this, 'team_home_other')" required>
      <?php foreach ($teams as $t): ?>
        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
      <?php endforeach; ?>
      <option value="other">-- Other --</option>
    </select>
    <input type="text" class="form-control mt-2 d-none" name="team_home_other" id="team_home_other" placeholder="Enter new home team">
  </div>

  <div class="mb-3">
    <label class="form-label">Away Team</label>
    <select name="away_home_id" class="form-select" onchange="toggleOther(this, 'team_away_other')" required>
      <?php foreach ($teams as $t): ?>
        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
      <?php endforeach; ?>
      <option value="other">-- Other --</option>
    </select>
    <input type="text" class="form-control mt-2 d-none" name="team_away_other" id="team_away_other" placeholder="Enter new away team">
  </div>

  <div class="mb-3">
    <label class="form-label">Prediction</label>
    <select name="prediction_text" class="form-select" onchange="toggleOther(this, 'tip_other')" required>
      <?php foreach ($tips as $tip): ?>
        <option value="<?= htmlspecialchars($tip) ?>"><?= htmlspecialchars($tip) ?></option>
      <?php endforeach; ?>
      <option value="other">-- Other --</option>
    </select>
    <input type="text" class="form-control mt-2 d-none" name="tip_other" id="tip_other" placeholder="Enter custom prediction">
  </div>

  <div class="mb-3">
    <label class="form-label">Odds</label>
    <input type="text" class="form-control" name="odds" required>
  </div>

  <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-bullseye"></i> Submit Prediction</button>
</form>

    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container">
    <?php include '../vip/partials/footer.php'; ?>
  </div>
</footer>
<script>
  function toggleOther(selectEl, inputId) {
    const inputEl = document.getElementById(inputId);
    if (selectEl.value === 'other') {
      inputEl.classList.remove('d-none');
      inputEl.required = true;
    } else {
      inputEl.classList.add('d-none');
      inputEl.required = false;
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'partials/sweetalert.php'; ?>
</body>
</html>
