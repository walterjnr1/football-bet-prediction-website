<?php
include('config.php');
$current_date = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Free Predictions-<?php echo $app_name; ?></title>
    <?php include('partials/head.php'); ?>
</head>
<body>
    <div class="topbar">
        <?php include('partials/topbar.php'); ?>
    </div>

    <div class="logo">
        <img src="<?php echo $app_logo; ?>" alt="Logo">
    </div>

    <nav class="navbar">
        <?php include('partials/navbar.php'); ?>
    </nav>

    <section class="hero">
        <h1>Free Football Tips &amp; Predictions</h1>
        <p>Get daily free football predictions from our expert tipsters. All tips are carefully analyzed and 100% free!</p>
        <p>&nbsp;</p>
    </section>

    <section class="predict-section">
                <p>&nbsp;</p>

        <h2>Free Football Predictions & Expert Tips</h2>
        <p>Stay ahead of the game with free football predictions, analysis, and daily insights.</p>
        <button class="become-tipster-btn"><i class="fas fa-user-plus"></i> Become a Tipster</button>
    </section>

    <section class="booking-section">
    <h2>Upcoming Free Predictions (Pending Results)</h2>
    <div class="free-table">
        <?php
        $query = "
            SELECT 
                p.id AS prediction_id,
                p.prediction_text,
                p.match_date,
                th.name AS home_team,
                ta.name AS away_team,
                l.id AS league_id,
                l.name AS league_name,
                l.country AS league_country
            FROM predictions p
            JOIN teams th ON p.team_home_id = th.id
            JOIN teams ta ON p.team_away_id = ta.id
            JOIN leagues l ON p.league_id = l.id
            WHERE p.type = 'free'
              AND LOWER(p.result) = 'pending'
            ORDER BY p.match_date ASC
        ";

        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $pendingPredictions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group by league name and country
        $groupedPending = [];
        foreach ($pendingPredictions as $row) {
            $leagueKey = $row['league_name'] . ' (' . $row['league_country'] . ')';
            $groupedPending[$leagueKey][] = $row;
        }

        if (!empty($groupedPending)) {
            foreach ($groupedPending as $league => $matches) {
                echo "<h4 style='margin-top:25px;'>$league</h4>";
                echo "<table>
                        <thead>
                            <tr>
                                <th>Match</th>
                                <th>Prediction</th>
                                <th>Match Date</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($matches as $match) {
                    $matchDate = date('M d, Y', strtotime($match['match_date']));
                    echo "<tr>
                            <td>{$match['home_team']} vs {$match['away_team']}</td>
                            <td>{$match['prediction_text']}</td>
                            <td>{$matchDate}</td>
                          </tr>";
                }
                echo "</tbody></table>";
            }
        } else {
            echo "<p>No pending free predictions found.</p>";
        }
        ?>

          <p>&nbsp;</p>
    <button class="read-more-btn" onClick="window.location.href='index#register-vip';">Get VIP Predictions</button>

    </div>
</section>


    <!-- Booking Codes Section -->
    <section class="booking-section">
        <h2>Active Free Booking Codes</h2>
        <p>Use our booking codes to place bets directly with your preferred bookmakers. Valid only within the specified match period.</p>
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
                            <td colspan="4">No active free bookings available at the moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include('partials/whatsapp.php'); ?>

    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
</body>
</html>
 