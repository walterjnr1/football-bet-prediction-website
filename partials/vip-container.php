<?php
// Fetch latest match date
$latestDateStmt = $dbh->query("SELECT DATE(match_date) as next_date FROM predictions WHERE type = 'fixed' ORDER BY match_date DESC LIMIT 1");
$nextDateRow = $latestDateStmt->fetch(PDO::FETCH_ASSOC);
$nextMatchDateFormatted = $nextDateRow ? date('l, F j, Y', strtotime($nextDateRow['next_date'] . ' +1 day')) : 'N/A';

// Fetch latest fixed matches for most recent date
$latestFixedDateStmt = $dbh->query("SELECT DATE(match_date) as match_date FROM predictions WHERE type = 'fixed' ORDER BY match_date DESC LIMIT 2");
$fixedDateRow = $latestFixedDateStmt->fetch(PDO::FETCH_ASSOC);

if ($fixedDateRow) {
    $matchDate = $fixedDateRow['match_date'];
    $formattedMatchDate = date('l, F j, Y', strtotime($matchDate));

    // Fetch match results for that date
    $matchesQuery = "
        SELECT 
            p.match_date, p.odds, p.score, p.result,
            th.name AS home_team, ta.name AS away_team,
            l.name AS league_name, l.country
        FROM predictions p
        JOIN teams th ON p.team_home_id = th.id
        JOIN teams ta ON p.team_away_id = ta.id
        JOIN leagues l ON p.league_id = l.id
        WHERE p.type = 'fixed' AND DATE(p.match_date) = ?
        ORDER BY p.match_date ASC
    ";
    $stmt = $dbh->prepare($matchesQuery);
    $stmt->execute([$matchDate]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Section Starts -->
<h2 class="vip-title">Next Fixed Match scheduled for - <?= htmlspecialchars($nextMatchDateFormatted) ?></h2> 

<?php if (!empty($matches)): ?>
<div class="vip-section">
  <div class="vip-header">
    <h3>Past VIP Results - <?= htmlspecialchars($formattedMatchDate) ?></h3>
    <a href="Fixed">See More &gt;&gt;&gt;</a>
  </div>

  <div class="vip-grid">
    <?php foreach ($matches as $match): 
      $time = date('H:i', strtotime($match['match_date']));
      $league = $match['country'] . ' ' . $match['league_name'];
      $status = ucfirst(strtolower($match['result']));
      $statusClass = strtolower($status) === 'won' ? 'status-won' : 'status-lose';
    ?>
      <div class="vip-card">
        <div class="vip-meta">
          <span class="time"><?= $time ?></span>
          <span class="league"><?= htmlspecialchars($league) ?></span>
        </div>
        <div class="teams"><?= htmlspecialchars($match['home_team']) ?> <span class="vs">VS</span> <?= htmlspecialchars($match['away_team']) ?></div>
        <div class="details">
          <span class="odds">ODDS: <?= htmlspecialchars($match['odds']) ?></span>
          <span class="status <?= $statusClass ?>"><?= $status ?></span>
        </div>
        <div class="score"><?= htmlspecialchars($match['score']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <p class="confirm-link"><a href="Tickets">*click here to confirm our winning tickets*</a></p>
</div>
<?php else: ?>
  <p>No VIP results found.</p>
<?php endif; ?>

