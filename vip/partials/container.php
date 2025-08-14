<?php

// Subscription details
$sql = " SELECT s.end_date, p.name AS plan_name 
         FROM subscriptions s 
         JOIN plans p ON s.plan_id = p.id
         WHERE s.user_id = :user_id 
         ORDER BY s.id DESC 
         LIMIT 1";
$stmt = $dbh->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$subscription = $stmt->fetch(PDO::FETCH_ASSOC);

$expiry_date = $subscription['end_date'] ?? null;
$plan_name   = $subscription['plan_name'] ?? null;

$is_expired = false;
if ($expiry_date) {
    $is_expired = (strtotime($expiry_date) < time());
}
?>
<div class="main-content">
    <div class="cards-row" id="loading-cards">
        
        <!-- Subscription Overview Card -->
        <div class="card" style="padding: 15px; display: flex; flex-direction: column; align-items: flex-start;">
            <h3 style="margin-top:0; margin-bottom: 8px;">Subscription Details</h3>

            <!-- Current Plan -->
            <div style="margin-bottom: 6px; font-size: 16px;">
                Current Plan: 
                <?php if ($is_expired || !$plan_name): ?>
                    <span style="background:#dc3545; color:#fff; padding:2px 6px; border-radius:4px; font-size:14px;">
                        Expired
                    </span>
                <?php else: ?>
                    <?php echo htmlspecialchars($plan_name); ?>
                <?php endif; ?>
            </div>

            <!-- Expiry Date -->
            <div style="font-size: 16px; font-weight: bold; margin-bottom: 12px;">
                Expiry Date: 
                <?php 
                if ($expiry_date) {
                    echo date("F j, Y", strtotime($expiry_date));
                } else {
                    echo 'N/A';
                }
                ?>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px;">
                <button 
                    style="background:#007bff; color:#fff; padding:8px 12px; border:none; border-radius:4px; cursor:pointer;
                    <?php echo (!$is_expired && $plan_name) ? 'opacity:0.6; cursor:not-allowed;' : ''; ?>"
                    <?php echo (!$is_expired && $plan_name) ? 'disabled' : 'onclick="window.location.href=\'buy-plan\'"'; ?>>
                    Buy Plan
                </button>
            </div>
        </div>

        <!-- Wallet Balance Card -->
        <div class="card" style="padding: 15px; display: flex; flex-direction: column; align-items: flex-start;">
            <h3 style="margin-top:0; margin-bottom: 15px;">Wallet Balance</h3>
            <div style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">
                N<?php echo isset($row_user['wallet_balance']) ? number_format($row_user['wallet_balance'], 2) : '0.00'; ?>
            </div>
            <div style="display: flex; gap: 10px;">
                <button id="btn-topup" style="background:#28a745; color:#fff; padding:8px 12px; border:none; border-radius:4px; cursor:pointer;">
                    Top Up
                </button>
            </div>
        </div>

    </div>


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
            <input type="text" name="full_name" value="<?php echo isset($row_user['full_name']) ? htmlspecialchars($row_user['full_name']) : ''; ?>" required>

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

<?php include 'partials/whatsapp.php'; ?>

<!-- Top Up Modal (hidden by default) -->
<div id="topup-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:420px; border-radius:8px; padding:20px; box-shadow:0 8px 24px rgba(0,0,0,0.2); position:relative;">
        <button id="topup-close" style="position:absolute; right:12px; top:12px; background:transparent; border:none; font-size:18px; cursor:pointer;">&times;</button>
        <h3 style="margin-top:0;">Top Up Wallet</h3>
        <p style="margin-bottom:10px; color:#555;">
            Enter the amount you'd like to add to your wallet. After clicking <strong>Pay now</strong> you'll be redirected to complete the payment securely via Paystack.
        </p>

        <form id="topup-form">
            <label for="topup-amount">Amount (NGN)</label>
            <input id="topup-amount" name="amount" type="number" min="50" step="0.01" placeholder="e.g. 500.00"
                required style="width:60%; padding:10px; margin:8px 0 16px 0; border:1px solid #ddd; border-radius:4px;">

            <div style="font-size:14px; color:#666; margin-bottom:14px;">
                Payment is secured by Paystack.
            </div>

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <button type="button" id="topup-cancel" style="background:#6c757d; color:#fff; padding:8px 12px; border:none; border-radius:4px; cursor:pointer;">Cancel</button>
                <button type="submit" id="topup-pay" style="background:#007bff; color:#fff; padding:8px 12px; border:none; border-radius:4px; cursor:pointer;">Pay now</button>
            </div>
        </form>
    </div>
</div>

<!-- Paystack inline script -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
(function(){
    const btnTopup = document.getElementById('btn-topup');
    const modal = document.getElementById('topup-modal');
    const closeBtn = document.getElementById('topup-close');
    const cancelBtn = document.getElementById('topup-cancel');
    const form = document.getElementById('topup-form');

    btnTopup.addEventListener('click', ()=>{
        modal.style.display = 'flex';
        document.getElementById('topup-amount').focus();
    });
    closeBtn.addEventListener('click', ()=> modal.style.display = 'none');
    cancelBtn.addEventListener('click', ()=> modal.style.display = 'none');

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const amountEl = document.getElementById('topup-amount');
        let amount = parseFloat(amountEl.value);
        if (!amount || amount < 50) {
            alert('Please enter a valid amount (minimum N50).');
            amountEl.focus();
            return;
        }

        const amountKobo = Math.round(amount * 100);
        const reference = 'topup_' + Date.now() + '_<?php echo intval($user_id); ?>';

        var handler = PaystackPop.setup({
            key: '<?php echo addslashes($paystack_public_key); ?>',
            email: '<?php echo isset($row_user['email']) ? addslashes($row_user['email']) : ''; ?>',
            amount: amountKobo,
            ref: reference,
            metadata: {
                custom_fields: [
                    { display_name: "User ID", variable_name: "user_id", value: '<?php echo intval($user_id); ?>' }
                ]
            },
            onClose: function(){
                // user closed modal
            },
            callback: function(response){
                const ref = encodeURIComponent(response.reference || reference);
                const amt = encodeURIComponent(amount.toFixed(2));
                modal.style.display = 'none';
                window.location.href = 'verify-topup.php?reference=' + ref + '&amount=' + amt;
            }
        });

        handler.openIframe();
    });

})();
</script>
