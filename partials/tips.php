<?php
// Fetch last 6 free predictions with pending result
$sql = "
SELECT 
  p.match_date, 
  p.prediction_text,
  th.name AS home_team, 
  ta.name AS away_team,
  l.name AS league_name
FROM predictions p
JOIN teams th ON p.team_home_id = th.id
JOIN teams ta ON p.team_away_id = ta.id
JOIN leagues l ON p.league_id = l.id
WHERE p.type = 'free' AND p.result = 'pending'
ORDER BY p.match_date ASC
LIMIT 6
";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$freeTips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="tips-header">
  <h2>Free Tips - <?= date('l, F j, Y') ?></h2>
  <a href="Free" class="see-more">See More &gt;&gt;&gt;</a>
</div>

<div class="tips-grid">
  <?php if ($freeTips): ?>
    <?php foreach ($freeTips as $tip): ?>
      <div class="tip-card">
        <div class="tip-meta">
          <span class="icon">⚽ <?= date('H:i', strtotime($tip['match_date'])) ?></span>
          <span class="league"><?= htmlspecialchars($tip['league_name']) ?></span>
        </div>
        <div class="teams">
          <?= htmlspecialchars($tip['home_team']) ?> 
          <span class="vs">VS</span> 
          <?= htmlspecialchars($tip['away_team']) ?>
        </div>
        <div class="prediction">
          <span class="tip green"><?= htmlspecialchars($tip['prediction_text']) ?></span>
          <span class="score">?:?</span>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div>No free predictions available at the moment.</div>
  <?php endif; ?>
</div>
