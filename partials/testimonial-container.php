<div class="testimonials">
  <?php
    $stmt = $dbh->query("SELECT full_name, comment, rating, created_at FROM reviews ORDER BY created_at DESC LIMIT 10");
    while ($review = $stmt->fetch(PDO::FETCH_ASSOC)):
      $stars = str_repeat("⭐", intval($review['rating']));
      $date = date("F j, Y, g:i a", strtotime($review['created_at']));
  ?>
    <div class="testimonial">
      <p>"<?php echo htmlspecialchars($review['comment']); ?>"</p>
      <h4>- <strong><?php echo htmlspecialchars($review['full_name']); ?>.</strong></h4>
      <div class="stars"><?php echo $stars; ?></div>
      <span class="date"><?php echo $date; ?></span>
    </div>
  <?php endwhile; ?>
</div>
