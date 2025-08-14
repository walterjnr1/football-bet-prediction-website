<?php
// Fetch plans from database
try {
    $stmt = $dbh->prepare("SELECT id, name, amount, duration FROM plans ORDER BY amount ASC");
    $stmt->execute();
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching plans: " . $e->getMessage();
    $plans = [];
}

// Function to convert duration (in days) to human-readable format
function formatDuration($days) {
    if ($days < 7) {
        return $days . " Day" . ($days > 1 ? "s" : "");
    } elseif ($days == 7) {
        return "1 Week";
    } elseif ($days < 30) {
        return round($days / 7) . " Weeks";
    } elseif ($days == 30) {
        return "1 Month";
    } elseif ($days < 365) {
        return round($days / 30) . " Months";
    } elseif ($days == 365) {
        return "1 Year";
    } else {
        return round($days / 365) . " Years";
    }
}
?>

<h2>Our Pricing Plans</h2>
<div class="pricing-cards">

<?php foreach ($plans as $plan): ?>
    <div class="pricing-card">
        <div class="award-icon">
            <?php
            // Pick icon based on duration
            if ($plan['duration'] >= 365) {
                echo '<i class="fas fa-crown"></i>';
            } elseif ($plan['duration'] >= 30) {
                echo '<i class="fas fa-gem"></i>';
            } else {
                echo '<i class="fas fa-award"></i>';
            }
            ?>
        </div>
        <div class="plan-title"><?= htmlspecialchars(formatDuration($plan['duration'])) ?></div>
        <div class="plan-desc"><?= htmlspecialchars($plan['name']) ?></div>
        <div class="plan-price">₦<?= number_format($plan['amount'], 0) ?></div>
        <button class="subscribe-btn" onclick="window.location.href='vip/subscription?id=<?= $plan['id'] ?>';">Subscribe</button>
    </div>
<?php endforeach; ?>

</div>


<!-- Font Awesome for icons -->

<style>
    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
    }

    .pricing-cards {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .pricing-card {
        background: white;
        border-radius: 10px;
        padding: 40px 20px 30px;
        width: 280px;
        text-align: center;
        position: relative;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 25px rgba(0,0,0,0.15);
    }

    .award-icon {
        background: gold;
        color: white;
        border-radius: 50%;
        padding: 18px;
        font-size: 32px; /* bigger icon */
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .plan-title {
        font-size: 1.4em;
        font-weight: bold;
        margin-top: 25px;
    }

    .plan-desc {
        font-size: 0.95em;
        color: #555;
        margin: 10px 0 20px;
    }

    .plan-price {
        font-size: 1.6em;
        font-weight: bold;
        color: #0088cc;
        margin-bottom: 20px;
    }

    .subscribe-btn {
        background: #0088cc;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .subscribe-btn:hover {
        background: #006699;
    }
</style>
