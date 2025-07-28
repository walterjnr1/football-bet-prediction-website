<?php 
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// Handle review submission
if (isset($_POST['btnreview'])) {
    $full_name = $_POST['full_name'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    $sql = "INSERT INTO reviews (full_name, comment, rating) VALUES (:full_name, :comment, :rating)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':rating', $rating);

    if ($stmt->execute()) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Review submitted successfully!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to submit review. Please try again.'];
    }
    header("Location: index");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $app_name; ?> - VIP Dashboard</title>
    <?php include 'partials/head.php'; ?>
</head>
<body>

    <div class="navbar">
        <?php include 'partials/navbar.php'; ?>
    </div>

    <div class="welcome-bar">
        You are welcome to <?php echo $app_name; ?> VIP Room, we provide 2 fixed correct scores daily. Ticket price is...
    </div>

    <div class="schedule-bar">
        <?php
        $next_day = date('l, F jS Y', strtotime('+1 day'));
        echo "Next Fixed Match Scheduled for - $next_day";
        ?>
    </div>

    <div class="container">
        <?php include 'partials/container.php'; ?>
    </div>

   

    <script src="assets/js/dropdown-content.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <footer>
        <?php include 'partials/footer.php'; ?>
    </footer>

    <!-- ✅ SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>


</body>
</html>
