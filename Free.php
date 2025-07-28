<?php 
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title> Free Predictions - <?php echo $app_name; ?></title>
  <?php include('partials/head.php'); ?>

</head>

<body>
<div class="site-wrap">

  <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
      <div class="site-mobile-menu-close">
        <span class="icon-close2 js-menu-toggle"></span>
      </div>
    </div>
    <div class="site-mobile-menu-body"></div>
  </div>

  <header class="site-navbar py-4" role="banner">
    <div class="container">
      <div class="d-flex align-items-center">
        <div class="site-logo">
          <a href="index">
            <img src="<?php echo $app_logo; ?>" alt="Logo">
          </a>
        </div>
        <div class="ml-auto">
          <nav class="site-navigation position-relative text-right" role="navigation">
            <?php include('partials/navbar.php'); ?>
          </nav>
          <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right text-white"><span class="icon-menu h3 text-white"></span></a>
        </div>
      </div>
    </div>
  </header>

  <div class="hero overlay" style="background-image: url('images/bg_3.jpg');">
    <div class="container">
      <div class="row align-items-center">    
        <div class="col-lg-5 mx-auto text-center">
          <h1 class="text-white">FREE ODDS</h1>
          <p>At <?php echo $app_name; ?> we bring you real-time match updates, team news, transfer rumors, and expert insights. Whether you’re checking scores, reading match previews, or analyzing player performance—we’ve got everything a true football fan needs.</p>
        </div>
      </div>
    </div>
  </div> <br> 

  <div class="container">
    <div class="section-title">Past Free Matches</div>
    <?php
    $nextDay = date('l, F j, Y', strtotime('+1 day'));
    ?>
    <div class="next-match">Next Free Match scheduled for - <?php echo $nextDay; ?></div>

    <?php
    // Pagination Setup
    $limit = 5; // number of days per page
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $limit;

    // Total date count
    $totalDatesStmt = $dbh->query("SELECT COUNT(DISTINCT DATE(match_date)) AS total FROM predictions WHERE type = 'free'");
    $totalDates = $totalDatesStmt->fetchColumn();
    $totalPages = ceil($totalDates / $limit);

    // Get paginated dates
    $datesQuery = "SELECT DISTINCT DATE(match_date) AS match_date_only FROM predictions WHERE type = 'free' ORDER BY match_date_only DESC LIMIT $limit OFFSET $offset";
    $datesStmt = $dbh->prepare($datesQuery);
    $datesStmt->execute();
    $datesResult = $datesStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($datesResult && count($datesResult) > 0) {
        echo '<div class="row"><div class="main-content" style="flex: 1;">';
        foreach ($datesResult as $dateRow) {
            $matchDateOnly = $dateRow['match_date_only'];
            $formattedDate = date('l, F j, Y', strtotime($matchDateOnly));
            echo '<div class="vip-header">Past Free Matches - ' . $formattedDate . '</div>';
            echo '<div class="match-grid">';

            $matchesQuery = "
                SELECT 
                    p.id, p.sport, p.match_date, p.odds, p.prediction_text, p.score, p.result,
                    l.name AS league_name, l.country,
                    th.name AS home_team, ta.name AS away_team
                FROM predictions p
                JOIN leagues l ON p.league_id = l.id
                JOIN teams th ON p.team_home_id = th.id
                JOIN teams ta ON p.team_away_id = ta.id
                WHERE p.type = 'free' AND DATE(p.match_date) = ?
                ORDER BY p.match_date ASC
            ";
            $matchesStmt = $dbh->prepare($matchesQuery);
            $matchesStmt->execute([$matchDateOnly]);
            $matchesResult = $matchesStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($matchesResult as $match) {
                $score = $match['score'] ?? '-';
                $result = strtolower($match['result'] ?? 'pending');
                $matchTime = date('H:i', strtotime($match['match_date']));
                $labelClass = $result === 'won' ? 'won' : 'lose';

                echo '<div class="match-card">';
                echo '<span class="match-time">' . $matchTime . '</span>';
                echo '<span class="league-label">' . htmlspecialchars($match['country'] . ' ' . $match['league_name']) . '</span>';
                echo '<div class="match-info">' . htmlspecialchars($match['home_team']) . ' <span style="color: red;">VS</span> ' . htmlspecialchars($match['away_team']) . '</div>';
                echo '<span class="odds-label">ODDS: ' . htmlspecialchars($match['odds']) . '</span>';
                echo '<span class="result-label ' . $labelClass . '">' . strtoupper($result) . '</span>';
                echo '<div class="score">' . $score . '</div>';
                echo '</div>';
            }
            echo '</div>'; // .match-grid
        }
        echo '</div></div>'; // .main-content .row

        // Pagination Controls
        echo '<div class="pagination">';
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '">&laquo; Prev</a>';
        }
        for ($p = 1; $p <= $totalPages; $p++) {
            $active = ($p == $page) ? 'active' : '';
            echo '<a href="?page=' . $p . '" class="' . $active . '">' . $p . '</a>';
        }
        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '">Next &raquo;</a>';
        }
        echo '</div>';
    } else {
        echo '<div>No past free matches found.</div>';
    }
    ?>
  </div>

    <?php include('partials/whatsapp.php'); ?>


  <footer class="footer-section">
    <?php include('partials/footer.php'); ?>
  </footer>

</div>
<!-- .site-wrap -->
</body>
</html>
