<h2>User Testimonials</h2>
<div class="testimonial-cards">
    <?php
    // Fetch testimonials from reviews table
    $stmt = $dbh->prepare("SELECT full_name, comment, rating, created_at FROM reviews where type='testimonial' ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reviews as $review):
        $fullname = htmlspecialchars($review['full_name']);
        $comment = htmlspecialchars($review['comment']);
        $rating = floatval($review['rating']);
        $date = date('F j, Y', strtotime($review['created_at'])); // Format the date

        // Determine full stars, half stars, and empty stars
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;
    ?>
        <div class="testimonial-card">
            <div class="testimonial-fullname"><?php echo $fullname; ?></div>
            <div class="testimonial-rating">
                <?php
                for ($i = 0; $i < $fullStars; $i++) {
                    echo '<i class="fas fa-star"></i>';
                }
                if ($halfStar) {
                    echo '<i class="fas fa-star-half-alt"></i>';
                }
                for ($i = 0; $i < $emptyStars; $i++) {
                    echo '<i class="far fa-star"></i>';
                }
                echo " {$rating}/5";
                ?>
            </div>
            <div class="testimonial-comments" style="text-align: justify;">
                "<?php echo $comment; ?>"
            </div>
            <div class="testimonial-date">
            <br>
            <strong>Posted on <?php echo $date; ?></strong>
            </div>
        </div>
    <?php endforeach; ?>
</div>
