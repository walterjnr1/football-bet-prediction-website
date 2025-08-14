<?php 
include('../inc/config.php');
include('../inc/email_dashboard.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}
$wallet_balance = $row_user['wallet_balance'] ?? 0;

// Fetch available plans
$stmt_plans = $dbh->query("SELECT * FROM plans ORDER BY amount ASC");
$plans = $stmt_plans->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Buy Plan - <?php echo htmlspecialchars($app_name); ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/others.css" />
  <link rel="shortcut icon" href="../<?php echo $app_logo; ?>" type="image/x-icon" />
  <script src="https://js.paystack.co/v1/inline.js"></script>
</head>
<body>
  
  <div class="navbar">
    <?php include 'partials/nav.php'; ?>
  </div>

  <div class="container">
    <div class="change-password-card">
      <h2>Choose a Plan</h2>

      <p><strong>Your Wallet Balance:</strong> ₦<?php echo number_format($wallet_balance, 2); ?></p>

      <?php if (count($plans) > 0): ?>
        <?php foreach ($plans as $plan): ?>
          <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
            <h3><?php echo htmlspecialchars($plan['name']); ?></h3>
            <p><strong>Price:</strong> ₦<?php echo number_format($plan['amount'], 2); ?></p>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($plan['duration']); ?> days</p>
            
            <!-- Pay with Card -->
            <button type="button" 
              onclick="payWithPaystack(<?php echo $plan['id']; ?>, '<?php echo htmlspecialchars($plan['name']); ?>', <?php echo $plan['amount']; ?>)" 
              style="background:#2d8cf0;color:#fff;border:none;padding:10px 20px;border-radius:5px;cursor:pointer; margin-bottom:5px;">
              Pay with Card
            </button>
            
            <!-- Pay from Wallet -->
            <form action="wallet-subscribe.php" method="POST" style="display:inline;">
              <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
              <button type="submit" 
                style="background:#28a745;color:#fff;border:none;padding:10px 20px;border-radius:5px;cursor:pointer;"
                <?php echo ($wallet_balance < $plan['amount']) ? 'disabled title="Insufficient balance"' : ''; ?>>
                Pay from Wallet
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No plans available at the moment.</p>
      <?php endif; ?>
    </div>
  </div>

  <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

  <!-- SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>

<script>
function payWithPaystack(planId, planName, amount) {
    var handler = PaystackPop.setup({
        key: '<?php echo $paystack_public_key; ?>',
        email: '<?php echo $row_user['email']; ?>',
        amount: amount * 100,
        currency: "NGN",
        ref: 'PLAN_' + planId + '_' + Math.floor((Math.random() * 1000000000) + 1),
        callback: function(response){
            window.location.href = 'verify-plan.php?reference=' + response.reference + '&plan_id=' + planId;
        },
        onClose: function(){
            alert('Transaction cancelled.');
        }
    });
    handler.openIframe();
}
</script>

</body>
</html>
