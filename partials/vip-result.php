<?php
// Fetch VIP results using PDO
$sql = "SELECT id, result_date, outcome FROM vip_results ORDER BY result_date DESC LIMIT 6";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>✅ VIP RESULTS</h3>
<div class="results-grid">
  <?php if (!empty($results)): ?>
    <?php foreach ($results as $row): 
      $day = strtoupper(date('D', strtotime($row['result_date'])));
      $date = date('d/m', strtotime($row['result_date']));
      $icon = ($row['outcome'] === 'won' || $row['outcome'] === '✔️') ? '✔️' : '❌';
    ?>
      <div class="day-box">
        <div class="tick-circle"><?php echo $icon; ?></div>
        <span class="day"><?php echo $day; ?></span>
        <span class="date"><?php echo $date; ?></span>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No VIP results found.</p>
  <?php endif; ?>
</div>

<a href="Fixed" class="view-results-btn" id="joinBtn" target="_blank">View All Results</a>
