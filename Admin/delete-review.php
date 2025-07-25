<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Check if review exists
    $stmt = $dbh->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($review) {
        // Delete record
        $deleteStmt = $dbh->prepare("DELETE FROM reviews WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            // Log activity
            $action = "Deleted review with ID $id on: $current_date";
            log_activity($dbh, $user_id, $action, 'reviews', $id, $ip_address);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Review deleted successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to delete review.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Review not found.'];
    }
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid review ID.'];
}

// Redirect back to reviews listing
header("Location: review-record");
exit;
?>
