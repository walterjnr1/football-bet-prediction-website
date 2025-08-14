<?php
include('config.php');

// Set current date
$current_date = date('Y-m-d');

// Handle AJAX request for predictions
if (isset($_GET['ajax']) && $_GET['ajax'] === 'predictions') {
    header('Content-Type: application/json');
    $date = isset($_GET['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date']) ? $_GET['date'] : $current_date;
    $query = "
        SELECT 
            p.id AS prediction_id,
            p.match_date,
            p.prediction_text,
            p.odds,
            u.full_name AS tipster,
            th.name AS home_team,
            ta.name AS away_team,
            l.name AS league_name,
            l.country AS league_country
        FROM predictions p
        JOIN users u ON p.user_id = u.id
        JOIN teams th ON p.team_home_id = th.id
        JOIN teams ta ON p.team_away_id = ta.id
        JOIN leagues l ON p.league_id = l.id
        WHERE p.type = 'free' 
          AND p.result = 'pending'
          AND DATE(p.match_date) = ?
        ORDER BY l.name, p.match_date
    ";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$date]);
    $predictions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];
    foreach ($predictions as $row) {
        $leagueKey = $row['league_name'] . ' (' . $row['league_country'] . ')';
        $grouped[$leagueKey][] = $row;
    }
    echo json_encode($grouped);
    exit;
}

// Handle review form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnreview'])) {
    $fullname = trim($_POST['fullname']);
    $comment  = trim($_POST['comment']);
    $email  = trim($_POST['email']);
    $rating   = intval($_POST['rating']);

    if ($fullname && $comment && $email && $rating >= 1 && $rating <= 5) {
        $stmt = $dbh->prepare("INSERT INTO reviews (full_name, email, comment, rating, type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$fullname, $email, $comment, $rating, 'review']);
        $reviewId = $dbh->lastInsertId();

        // Log activity
        $action = "Saved a review on: " . $current_date;
        log_activity($dbh, $user['id'], $action, 'reviews', $reviewId, $_SERVER['REMOTE_ADDR']);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Thank you! Your review has been submitted.'];
        header("Location: index");
        exit;
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Please fill out all fields correctly'];
        header("Location: index");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $app_name; ?></title>
<?php include('partials/head.php')  ?>

</head>
<body>
    <div class="topbar">
        <?php include('partials/topbar.php')  ?>
    </div>
    <div class="logo">
        <img src="<?php echo $app_logo; ?>" alt="Logo">
    </div>
    <nav class="navbar">
     <?php include('partials/navbar.php')  ?>
    </nav>
    <section class="hero">
      <?php include('partials/hero.php')  ?>
      <p>&nbsp;</p>
    </section>
    <section class="predict-section">
        <h2>&nbsp;</h2>
        <p>&nbsp;</p>
        <h2>Predict Football Matches &amp; Get Expert Tips</h2>
        <p>Stay ahead of the game with daily football predictions, analysis, and news.</p>
        <button class="become-tipster-btn" id="tipsterBtn">
            <i class="fas fa-user-plus"></i> Become a Tipster
        </button>
    </section>

    <!-- Main Content: Predictions + Review Form -->
    <div class="main-content-flex">
        <div class="predictions-table" style="flex:2 1 400px; min-width:320px; max-width:600px;">
            <h3>Today's Free Football Predictions</h3>
            <!-- Date Picker for predictions -->
            <div style="margin-bottom:15px; text-align:left;">
                <label for="pred_date" style="font-weight:bold;">Select Date:</label>
                <input type="date" id="pred_date" name="pred_date"
                    min="<?php echo $current_date; ?>"
                    value="<?php echo $current_date; ?>"
                    style="padding:4px 8px; border-radius:4px; border:1px solid #ccc;">
            </div>
            <div id="predictions_content">
                <!-- Predictions will be loaded here by AJAX -->
                <div style="text-align:center; color:#888; padding:1.5rem 0;">Loading predictions...</div>
            </div>
        </div>
        <div class="review-form-container">
            <h3>Leave a Review</h3>
            <form action="" method="POST">
                <div class="review-group">
                    <label for="fullname" class="review-label">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="review-input" required>
                </div>
                <div class="review-group">
                    <label for="email" class="review-label">Email</label>
                    <input type="email" id="email" name="email" class="review-input" required>
                </div>
                <div class="review-group">
                    <label for="comment" class="review-label">Comment</label>
                    <textarea id="comment" name="comment" rows="4" class="review-input" required></textarea>
                </div>
                <div class="review-group">
                    <label for="rating" class="review-label">Rating</label>
                    <select id="rating" name="rating" class="review-input" required>
                        <option value="">Select Rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                        <option value="4">⭐⭐⭐⭐ - Good</option>
                        <option value="3">⭐⭐⭐ - Average</option>
                        <option value="2">⭐⭐ - Poor</option>
                        <option value="1">⭐ - Terrible</option>
                    </select>
                </div>
                <button type="submit" name="btnreview" class="review-submit-btn">
                    Submit Review
                </button>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div id="underDevModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h3>Feature Under Development</h3>
            <p>We’re working hard to bring this feature to you soon. Stay tuned!</p>
        </div>
    </div>
    <style>
    /* ... (modal styles unchanged) ... */
    </style>
    <script>
    // Modal JS
    document.getElementById('tipsterBtn').addEventListener('click', function() {
        document.getElementById('underDevModal').style.display = 'block';
    });
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('underDevModal').style.display = 'none';
    });
    window.addEventListener('click', function(e) {
        if (e.target == document.getElementById('underDevModal')) {
            document.getElementById('underDevModal').style.display = 'none';
        }
    });

    // AJAX for predictions
    function loadPredictions(date) {
        var content = document.getElementById('predictions_content');
        content.innerHTML = '<div style="text-align:center; color:#888; padding:1.5rem 0;">Loading predictions...</div>';
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '?ajax=predictions&date=' + encodeURIComponent(date), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var grouped = JSON.parse(xhr.responseText);
                    var html = '';
                    if (Object.keys(grouped).length > 0) {
                        for (var league in grouped) {
                            html += "<h4 style='margin-top:30px;'>" + league + "</h4>";
                            html += "<table style='width:100%; border-collapse:collapse;'><thead><tr><th>Match</th><th>Prediction</th><th>Odds</th><th>Tipster</th></tr></thead><tbody>";
                            grouped[league].forEach(function(match) {
                                var odds = (match.odds !== null && match.odds !== '') ? match.odds : '-';
                                html += "<tr><td>" + match.home_team + " vs " + match.away_team + "</td><td>" + match.prediction_text + "</td><td>" + odds + "</td><td>" + match.tipster + "</td></tr>";
                            });
                            html += "</tbody></table>";
                        }
                    } else {
                        html = "<p>No predictions available for this date.</p>";
                    }
                    content.innerHTML = html;
                } else {
                    content.innerHTML = "<p style='color:red;'>Failed to load predictions.</p>";
                }
            }
        };
        xhr.send();
    }
    // On page load
    document.addEventListener('DOMContentLoaded', function() {
        var predDate = document.getElementById('pred_date');
        loadPredictions(predDate.value);
        predDate.addEventListener('change', function() {
            loadPredictions(this.value);
        });
    });
    </script>

    <section id="pricing" class="tipsters-section">
        <?php include('partials/pricing-plan.php'); ?>
    </section>
    <div class="predictions-results-container" style="display:flex; gap:32px; flex-wrap:wrap; justify-content:center; align-items:flex-start; width:100%; box-sizing:border-box;">
        <div class="results-table" style="flex:1 1 400px; min-width:320px; max-width:600px;">
        <h3>Previous VIP Matches Result</h3>
        <?php
        $today = date('Y-m-d');
        $query = "
        SELECT 
            p.match_date,
            p.prediction_text,
            p.score,
            p.result,
            th.name AS home_team,
            ta.name AS away_team,
            l.name AS league_name,
            l.country AS league_country
        FROM predictions p
        JOIN teams th ON p.team_home_id = th.id
        JOIN teams ta ON p.team_away_id = ta.id
        JOIN leagues l ON p.league_id = l.id
        WHERE p.type = 'fixed' 
          AND p.score IS NOT NULL 
          AND p.score != ''
          AND p.result != 'pending'
          AND DATE(p.match_date) < ?
        ORDER BY l.name, p.match_date DESC
        ";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$today]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedResults = [];
        foreach ($results as $row) {
            $leagueKey = $row['league_name'] . ' (' . $row['league_country'] . ')';
            $groupedResults[$leagueKey][] = $row;
        }

        if (!empty($groupedResults)) {
            foreach ($groupedResults as $league => $matches) {
                echo "<h4 style='margin-top:20px;'>$league</h4>";
                echo "<table style='width:100%; border-collapse:collapse;'>
                        <thead>
                            <tr>
                                <th>Match</th>
                                <th>Prediction</th>
                                <th>Result</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($matches as $match) {
                    $statusClass = (strtolower($match['result']) === 'won') ? 'status-won' : 'status-lose';
                    echo "<tr>
                            <td>{$match['home_team']} vs {$match['away_team']}</td>
                            <td>{$match['prediction_text']}</td>
                            <td>{$match['score']}</td>
                            <td class='{$statusClass}'>".ucfirst($match['result'])."</td>
                          </tr>";
                }
                echo "</tbody></table>";
            }
        } else {
            echo "<p>No previous VIP match results found.</p>";
        }
        ?>
        </div>
    </div>
    <section class="free-predictions-section">
        <h2>Free Predictions Results</h2>
        <div class="free-table">
            <?php
            $query = "
                SELECT 
                p.id AS prediction_id,
                p.prediction_text,
                p.result,
                th.name AS home_team,
                ta.name AS away_team,
                l.name AS league_name,
                l.country AS league_country
                FROM predictions p
                JOIN teams th ON p.team_home_id = th.id
                JOIN teams ta ON p.team_away_id = ta.id
                JOIN leagues l ON p.league_id = l.id
                WHERE p.type = 'free'
                  AND LOWER(p.result) != 'pending'
                ORDER BY p.match_date DESC
                LIMIT 5
            ";
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $predictions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $grouped = [];
            foreach ($predictions as $row) {
                $leagueKey = $row['league_name'] . ' (' . $row['league_country'] . ')';
                $grouped[$leagueKey][] = $row;
            }

            if (!empty($grouped)) {
                foreach ($grouped as $league => $matches) {
                    echo "<h4 style='margin-top:25px;'>$league</h4>";
                    echo "<table>
                            <thead>
                                <tr>
                                    <th>Match</th>
                                    <th>Prediction</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>";
                    foreach ($matches as $match) {
                        $statusClass = strtolower($match['result']) === 'won' ? 'status-won' :
                                       (strtolower($match['result']) === 'lose' ? 'status-lose' : 'status-pending');
                        echo "<tr>
                                <td>{$match['home_team']} vs {$match['away_team']}</td>
                                <td>{$match['prediction_text']}</td>
                                <td class=\"$statusClass\">".ucfirst($match['result'])."</td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                }
            } else {
                echo "<p>No Free prediction results available.</p>";
            }
            ?>
            <button class="read-more-btn" onClick="window.location.href='free-predictions'">View More</button>
        </div>
    </section>
    <section class="booking-section">
        <h2>Booking Codes &amp; Bookies</h2>
        <p>Use our booking codes to quickly place your bets with your favorite bookmakers. Copy the code and use it on the respective platform.</p>
        <div class="booking-table">
          <?php
    $bookmakers = [
        '1x bet' => 'images/1xbet-logo.png',
        '22bet' => 'images/22xbet-logo.jfif',
        'bet9ja' => 'images/bet9ja-logo.png',
        'betking' => 'images/betking-logo.jfif',
        'football.com' => 'images/football.com-logo.jfif',
        'paripesa' => 'images/paripesa-logo.jfif',
        'sportybet' => 'images/sportybet-logo.jfif'
    ];
    $stmt = $dbh->prepare("SELECT * FROM bookings WHERE type = 'free' AND :now BETWEEN match_start_date AND match_end_date ORDER BY id DESC");
    $stmt->execute(['now' => $current_date]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table>
        <thead>
            <tr>
                <th>Bookie</th>
                <th>Booking Code</th>
                <th>Country</th>
                <th>No. of Games</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): 
                    $bookie = strtolower($booking['bookmaker']);
                    $logo = $bookmakers[$bookie] ?? 'images/default-logo.png';
                ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($booking['bookmaker']) ?>" width="24" style="vertical-align:middle;">
                        <?= htmlspecialchars(ucwords($booking['bookmaker'])) ?>
                    </td>
                    <td><strong><?= htmlspecialchars($booking['code']) ?></strong></td>
                    <td><?= htmlspecialchars($booking['country']) ?></td>
                    <td><?= (int)$booking['no_matches'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No active free bookings available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
        </div>
    </section>
    <section class="upcoming-section">
       <?php include('partials/livescore.php'); ?>
    </section>
    <section id="news" class="news-section">
        <h2>Soccer News &amp; Insights</h2>
        <div class="news-cards">
            <?php
            $stmt = $dbh->prepare("SELECT id, title, content, image, published_at FROM news ORDER BY published_at DESC LIMIT 6");
            $stmt->execute();
            $newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($newsList as $news):
                $id = (int)$news['id'];
                $title = htmlspecialchars($news['title']);
                $image = htmlspecialchars($news['image']);
                $publishedAt = date("F j, Y", strtotime($news['published_at']));
                $rawContent = strip_tags($news['content'], '<p>');
                $shortContent = mb_strimwidth($rawContent, 0, 250, '...');
            ?>
                <div class="news-card">
                    <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
                    <h4><?php echo $title; ?></h4>
                    <div class="news-preview" style="text-align:justify;"><?php echo $shortContent; ?></div>
                    <small class="published-date">Published on <?php echo $publishedAt; ?></small><br>
                    <button class="read-more-btn" onClick="window.location.href='news?id=<?php echo $id; ?>';">Read More</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <!-- Testimonial Section -->
    <section class="testimonial-section">
       <?php include('partials/testimonial-container.php'); ?>
    </section>
    <?php include('partials/whatsapp.php'); ?>
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
    <!-- SweetAlert Toast Notification -->
    <?php include 'partials/sweetalert.php'; ?>
</body>
</html>
