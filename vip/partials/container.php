<?php
// Fetch user's payment stage
$user_id = $row_user['id'];
$sql_stage = "SELECT current_stage FROM vip_payment_stages WHERE user_id = :user_id LIMIT 1";
$stmt_stage = $dbh->prepare($sql_stage);
$stmt_stage->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt_stage->execute();
$current_stage = $stmt_stage->fetchColumn();

// Modal config based on stage
$modalConfig = [
    'initial' => [
        'onload' => [
            'title' => 'Congratulations!',
            'desc' => "Congratulations for your initial payment. You are one step closer to accessing today's premium tips. Please proceed to the next stage.",
            'link_text' => '(Click to View Match)',
            'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for clearance fee",
            'next_modal' => [
                'msg' => 'Oops you haven’t paid for clearance fee, Click here to pay.',
                'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for clearance fee"
            ]
        ]
    ],
    'clearance_fee' => [
        'onload' => [
            'title' => 'Congratulations!',
            'desc' => "Congratulations for paying your clearance fee. Please proceed to the next stage to unlock today's premium tips.",
            'link_text' => 'Click to Continue',
            'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for fifa fee",
            'next_modal' => [
                'msg' => 'Oops you haven’t paid for fifa fee, Click here to pay.',
                'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for fifa fee"
            ]
        ]
    ],
    'fifa_fee' => [
        'onload' => [
            'title' => 'Congratulations!',
            'desc' => "Congratulations for paying your FIFA fee. Please proceed to the next stage to unlock today's premium tips.",
            'link_text' => 'Click to Continue',
            'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for delivery fee",
            'next_modal' => [
                'msg' => 'Oops you haven’t paid for delivery fee, Click here to pay.',
                'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for delivery fee"
            ]
        ]
    ],
    'delivery_fee' => [
        'onload' => [
            'title' => 'Congratulations!',
            'desc' => "Congratulations for paying your delivery fee. Please proceed to the next stage to unlock today's premium tips.",
            'link_text' => 'Click to Continue',
            'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for delivery fee",
            'next_modal' => [
                'msg' => 'Oops you haven’t paid for delivery fee, Click here to pay.',
                'whatsapp_text' => "Good day, my Name is %s, a VIP user on your website %s. I would like to make payment for delivery fee"
            ]
        ]
    ],
    'completed' => [
        'onload' => [
            'title' => 'Congratulations!',
            'desc' => "You have successfully paid Delivery fee and qualified to view today's games. Enjoy your access to premium tips!",
            'link_text' => 'Click to view games',
            'whatsapp_text' => "",
            'next_modal' => [
                'msg' => "Today's Predictions",
                'show_predictions' => true
            ]
        ]
    ]
];

// Get config for current stage
$stageConfig = isset($modalConfig[$current_stage]) ? $modalConfig[$current_stage]['onload'] : null;
?>

<div class="main-content">
    <div class="cards-row" id="loading-cards">
        <div class="card"><div class="loader"></div></div>
        <div class="card"><div class="loader"></div></div>
    </div>

    <!-- Trigger Button -->
    <p>
        <a href="javascript:void(0);" id="stageTriggerBtn"
           style="font-weight: bold; color: #007bff; text-decoration: underline;">
            <?php
            // If completed, show "Click to view games"
            if ($current_stage === 'completed') {
                echo "Click to view games";
            } else {
                echo $stageConfig ? $stageConfig['link_text'] : "View Today's Tips";
            }
            ?>
        </a>
    </p>

    <!-- Stage Modal (on page load) -->
    <div id="stageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
        background-color:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:30px; border-radius:12px; width:90%; max-width:420px; box-shadow:0 10px 30px rgba(0,0,0,0.2); text-align:center; position:relative;">
            <h2 style="margin-top:0; color:#28a745;"><?php echo $stageConfig ? $stageConfig['title'] : ''; ?></h2>
            <p style="color:#444; font-size:15px; margin-bottom:20px;">
                <?php echo $stageConfig ? $stageConfig['desc'] : ''; ?>
            </p>
            <?php if ($stageConfig && !empty($stageConfig['whatsapp_text'])): ?>
                <button onclick="redirectToWhatsAppStage()" style="background-color:#051e0bff; color:white; border:none; padding:12px 24px; border-radius:6px; font-size:16px; font-weight:bold; cursor:pointer; width:100%; margin-bottom:12px;">
                    <i class="fab fa-whatsapp" style="margin-right:8px;"></i> Pay Now via WhatsApp
                </button>
            <?php endif; ?>
            <button onclick="closeStageModal()" style="background:transparent; color:#dc3545; border:none; font-size:14px; cursor:pointer; text-decoration:underline;">
                Close
            </button>
        </div>
    </div>

    <!-- Next Stage Modal -->
    <div id="nextStageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
        background-color:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:30px; border-radius:12px; width:90%; max-width:520px; box-shadow:0 10px 30px rgba(0,0,0,0.2); text-align:center; position:relative;">
            <h2 style="margin-top:0; color:#dc3545;">
                <?php
                if ($stageConfig && isset($stageConfig['next_modal']['show_predictions']) && $stageConfig['next_modal']['show_predictions']) {
                    echo "Today's Predictions";
                } else {
                    echo "Payment Required";
                }
                ?>
            </h2>
            <div style="color:#444; font-size:15px; margin-bottom:20px;">
                <?php
                if ($stageConfig && isset($stageConfig['next_modal']['show_predictions']) && $stageConfig['next_modal']['show_predictions']) {
                    // Display today's predictions
                    $today = date('Y-m-d');
                    $sql_pred = "
                        SELECT p.*, 
                               th.name AS home_team, 
                               ta.name AS away_team, 
                               l.name AS league_name, 
                               l.country AS league_country
                        FROM predictions p
                        JOIN teams th ON p.team_home_id = th.id
                        JOIN teams ta ON p.team_away_id = ta.id
                        JOIN leagues l ON p.league_id = l.id
                        WHERE DATE(p.match_date) = :today AND p.type = 'fixed'
                        ORDER BY p.match_date ASC
                    ";
                    $stmt_pred = $dbh->prepare($sql_pred);
                    $stmt_pred->bindValue(':today', $today);
                    $stmt_pred->execute();
                    $predictions = $stmt_pred->fetchAll(PDO::FETCH_ASSOC);

                    if ($predictions) {
                        echo '<div class="today-predictions">';
                        foreach ($predictions as $pred) {
                            echo '<div class="prediction-card" style="border:1px solid #eee; border-radius:8px; margin-bottom:16px; padding:12px; background:#f9f9f9;">';
                            echo '<div style="font-weight:bold; color:#007bff;">' . htmlspecialchars($pred['league_name']) . ' (' . htmlspecialchars($pred['league_country']) . ')</div>';
                            echo '<div style="margin:6px 0;"><i class="fas fa-futbol"></i> ' . (isset($pred['sport_type']) ? htmlspecialchars(ucfirst($pred['sport_type'])) : 'Football') . '</div>';
                            echo '<div><i class="fas fa-clock"></i> ' . date('H:i', strtotime($pred['match_date'])) . '</div>';
                            echo '<div style="margin:6px 0;">' . htmlspecialchars($pred['home_team']) . ' vs ' . htmlspecialchars($pred['away_team']) . '</div>';
                            echo '<div><strong>Prediction:</strong> ' . htmlspecialchars($pred['prediction_text']) . '</div>';
                            echo '<div><strong>Odds:</strong> ' . htmlspecialchars($pred['odds']) . '</div>';
                            echo '<div><strong>Result:</strong> ';
                            if ($pred['result'] === 'won') {
                                echo '<span style="color:green;font-weight:bold;"><i class="fas fa-check-circle"></i> Won</span>';
                            } elseif ($pred['result'] === 'lose') {
                                echo '<span style="color:red;font-weight:bold;"><i class="fas fa-times-circle"></i> Lose</span>';
                            } else {
                                echo '<span style="color:#888;">Pending</span>';
                            }
                            echo '</div>';
                            echo '<div><strong>Score:</strong> ' . htmlspecialchars($pred['score']) . '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo "No predictions available for today.";
                    }
                } else {
                    echo $stageConfig ? $stageConfig['next_modal']['msg'] : '';
                }
                ?>
            </div>
            <?php if ($stageConfig && isset($stageConfig['next_modal']['whatsapp_text'])): ?>
                <button onclick="redirectToWhatsAppNextStage()" style="background-color:#051e0bff; color:white; border:none; padding:12px 24px; border-radius:6px; font-size:16px; font-weight:bold; cursor:pointer; width:100%; margin-bottom:12px;">
                    <i class="fab fa-whatsapp" style="margin-right:8px;"></i> Pay Now via WhatsApp
                </button>
            <?php endif; ?>
            <button onclick="closeNextStageModal()" style="background:transparent; color:#dc3545; border:none; font-size:14px; cursor:pointer; text-decoration:underline;">
                Close
            </button>
        </div>
    </div>

    <!-- Today's Score Modal (for completed stage) -->
    <div id="todayScoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
        background-color:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:30px; border-radius:12px; width:90%; max-width:520px; box-shadow:0 10px 30px rgba(0,0,0,0.2); text-align:center; position:relative;">
            <h2 style="margin-top:0; color:#007bff;">Today's Score</h2>
            <div style="color:#444; font-size:15px; margin-bottom:20px;">
                <?php
                $today = date('Y-m-d');
                $sql_score = "
                    SELECT p.*, 
                           th.name AS home_team, 
                           ta.name AS away_team, 
                           l.name AS league_name, 
                           l.country AS league_country
                    FROM predictions p
                    JOIN teams th ON p.team_home_id = th.id
                    JOIN teams ta ON p.team_away_id = ta.id
                    JOIN leagues l ON p.league_id = l.id
                    WHERE DATE(p.match_date) = :today AND p.type = 'fixed'
                    ORDER BY p.match_date ASC
                ";
                $stmt_score = $dbh->prepare($sql_score);
                $stmt_score->bindValue(':today', $today);
                $stmt_score->execute();
                $scores = $stmt_score->fetchAll(PDO::FETCH_ASSOC);

                if ($scores) {
                    echo '<div class="today-scores">';
                    foreach ($scores as $score) {
                        echo '<div class="score-card" style="border:1px solid #eee; border-radius:8px; margin-bottom:16px; padding:12px; background:#f9f9f9;">';
                        echo '<div style="font-weight:bold; color:#007bff;">' . htmlspecialchars($score['league_name']) . ' (' . htmlspecialchars($score['league_country']) . ')</div>';
                        echo '<div style="margin:6px 0;"><i class="fas fa-futbol"></i> ' . (isset($score['sport_type']) ? htmlspecialchars(ucfirst($score['sport_type'])) : 'Football') . '</div>';
                        echo '<div><i class="fas fa-clock"></i> ' . date('H:i', strtotime($score['match_date'])) . '</div>';
                        echo '<div style="margin:6px 0;">' . htmlspecialchars($score['home_team']) . ' vs ' . htmlspecialchars($score['away_team']) . '</div>';
                        echo '<div><strong>Odd:</strong> ' . htmlspecialchars($score['odds']) . '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo "No scores available for today.";
                }
                ?>
            </div>
            <button onclick="closeTodayScoreModal()" style="background:transparent; color:#dc3545; border:none; font-size:14px; cursor:pointer; text-decoration:underline;">
                Close
            </button>
        </div>
    </div>

    <script>
    // Show stage modal on page load if config exists
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($stageConfig): ?>
            document.getElementById('stageModal').style.display = 'flex';
        <?php endif; ?>
    });

    function closeStageModal() {
        document.getElementById('stageModal').style.display = 'none';
    }

    // Handle trigger button click
    document.getElementById('stageTriggerBtn').onclick = function() {
        <?php if ($current_stage === 'completed'): ?>
            document.getElementById('todayScoreModal').style.display = 'flex';
        <?php else: ?>
            document.getElementById('nextStageModal').style.display = 'flex';
        <?php endif; ?>
    };

    function closeNextStageModal() {
        document.getElementById('nextStageModal').style.display = 'none';
    }

    function closeTodayScoreModal() {
        document.getElementById('todayScoreModal').style.display = 'none';
    }

    function redirectToWhatsAppStage() {
        <?php
        $name = rawurlencode($row_user['full_name']);
        $siteName = rawurlencode($row_website['site_name']);
        $phone = rawurlencode($row_website['whatsapp_phone']);
        $whatsapp_text = $stageConfig && isset($stageConfig['whatsapp_text'])
            ? sprintf($stageConfig['whatsapp_text'], $row_user['full_name'], $row_website['site_name'])
            : '';
        ?>
        const phone = "<?php echo $phone; ?>";
        const text = "<?php echo addslashes($whatsapp_text); ?>";
        const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
        window.open(whatsappUrl, '_blank');
        setTimeout(function () {
            window.location.href = 'index';
        }, 1000);
    }

    function redirectToWhatsAppNextStage() {
        <?php
        $name = rawurlencode($row_user['full_name']);
        $siteName = rawurlencode($row_website['site_name']);
        $phone = rawurlencode($row_website['whatsapp_phone']);
        $whatsapp_text_next = $stageConfig && isset($stageConfig['next_modal']['whatsapp_text'])
            ? sprintf($stageConfig['next_modal']['whatsapp_text'], $row_user['full_name'], $row_website['site_name'])
            : '';
        ?>
        const phone = "<?php echo $phone; ?>";
        const text = "<?php echo addslashes($whatsapp_text_next); ?>";
        const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
        window.open(whatsappUrl, '_blank');
        setTimeout(function () {
            window.location.href = 'index';
        }, 1000);
    }
    </script>

    <div class="past-label">
        Past successful fixed matches
    </div>

    <?php
    // Pagination setup
    $limit = 3;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $limit;

    // Get distinct match dates for 'fixed' predictions with result 'won' or 'lose'
    $sql_dates = "
        SELECT DISTINCT DATE(match_date) AS match_day
        FROM predictions
        WHERE type = 'fixed' AND result IN ('won', 'lose')
        ORDER BY match_day DESC
        LIMIT :limit OFFSET :offset
    ";
    $stmt_dates = $dbh->prepare($sql_dates);
    $stmt_dates->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt_dates->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt_dates->execute();
    $days = $stmt_dates->fetchAll(PDO::FETCH_COLUMN);

    // Total pages for pagination
    $sql_count = "
        SELECT COUNT(DISTINCT DATE(match_date)) AS total_days
        FROM predictions
        WHERE type = 'fixed' AND result IN ('won', 'lose')
    ";
    $total_days = $dbh->query($sql_count)->fetchColumn();
    $total_pages = ceil($total_days / $limit);

    if ($days):
    ?>
    <div class="past-matches">
        <?php foreach ($days as $day): ?>
            <?php
            $sql_matches = "
                SELECT p.*, 
                       th.name AS home_team, 
                       ta.name AS away_team, 
                       l.name AS league_name, 
                       l.country AS league_country
                FROM predictions p
                JOIN teams th ON p.team_home_id = th.id
                JOIN teams ta ON p.team_away_id = ta.id
                JOIN leagues l ON p.league_id = l.id
                WHERE DATE(p.match_date) = :match_day AND p.type = 'fixed' AND p.result IN ('won', 'lose')
                ORDER BY p.match_date ASC
            ";
            $stmt_matches = $dbh->prepare($sql_matches);
            $stmt_matches->bindValue(':match_day', $day);
            $stmt_matches->execute();
            $matches = $stmt_matches->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="past-matches-day">
                <div class="match-label"><?php echo date('l, m/d', strtotime($day)); ?></div>
                <div class="match-day-cards">
                    <?php foreach ($matches as $match): ?>
                        <div class="match-card">
                            <div class="league">
                                <i class="fas fa-trophy"></i>
                                <?php echo htmlspecialchars($match['league_name'] . ' (' . $match['league_country'] . ')'); ?>
                            </div>
                            <div class="sport-type">
                                <i class="fas fa-futbol"></i>
                                <?php echo isset($match['sport_type']) ? htmlspecialchars(ucfirst($match['sport_type'])) : 'Football'; ?>
                            </div>
                            <div class="time">
                                <i class="fas fa-clock"></i>
                                <?php echo date('H:i', strtotime($match['match_date'])); ?>
                            </div>
                            <div class="teams">
                                <?php echo htmlspecialchars($match['home_team'] . ' vs ' . $match['away_team']); ?>
                            </div>
                            <div class="odds">
                                <i class="fas fa-coins"></i>
                                <?php echo htmlspecialchars($match['odds']); ?>
                            </div>
                            <div id="result">
                                <?php if ($match['result'] === 'won'): ?>
                                    <span style="float:right; display: flex; align-items: center;">
                                        <i class="fas fa-check-circle" style="color:green;font-weight:bold; margin-right: 6px;"></i>
                                        <span style="color:green;font-weight:bold;">Won</span>
                                    </span>
                                <?php elseif ($match['result'] === 'lose'): ?>
                                    <span style="float:right; display: flex; align-items: center;">
                                        <i class="fas fa-times-circle" style="color:red;font-weight:bold; margin-right: 6px;"></i>
                                        <span style="color:red;font-weight:bold;">Lose</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="score">
                                <?php echo htmlspecialchars($match['score']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="page-link">&laquo; Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="page-link<?php echo $i == $page ? ' active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="page-link">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="past-matches">
            <p>No successful fixed matches found.</p>
        </div>
    <?php endif; ?>

    <div class="confirmation-link">
        <a href="../index">Click here to confirm our winning tickets</a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <div class="vip-results">
        <?php
        $sql = "SELECT result_date, outcome FROM vip_results ORDER BY result_date DESC LIMIT 6";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        function getDayName($date) {
            return date('D', strtotime($date));
        }
        ?>
        <h4>Recent VIP Results</h4>
        <div class="result-grid">
            <?php foreach ($results as $row): ?>
                <div class="result-day <?= $row['outcome'] === 'won' ? 'won' : 'lose' ?>">
                    <div class="icon-square" style="background:<?= $row['outcome'] === 'won' ? '#28a745' : '#dc3545' ?>;color:#fff;">
                        <i class="fas <?= $row['outcome'] === 'won' ? 'fa-check' : 'fa-times' ?>"></i>
                    </div>
                    <div class="day"><?= getDayName($row['result_date']) ?></div>
                    <div class="date"><?= date('m/d', strtotime($row['result_date'])) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Review Form -->
    <div class="review-section">
        <h4>Leave a Review</h4>
        <form class="review-form" method="POST">
            <input type="text" name="fullname" value="<?php echo isset($row_user['full_name']) ? htmlspecialchars($row_user['full_name']) : ''; ?>" readonly>

            <label for="comment">Comment</label>
            <textarea id="comment" name="comment" placeholder="Write your comment" required></textarea>

            <label for="rating">Select Rating</label>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                <option value="4">⭐⭐⭐⭐ - Very Good</option>
                <option value="3">⭐⭐⭐ - Good</option>
                <option value="2">⭐⭐ - Fair</option>
                <option value="1">⭐ - Poor</option>
            </select>

            <button type="submit" name="btnreview">Submit</button>
        </form>
    </div>
</div>

<!-- WhatsApp Floating Icon -->
<a href="https://wa.me/<?php echo isset($row_website['whatsapp_phone']) ? htmlspecialchars($row_website['whatsapp_phone']) : ''; ?>" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>
