<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Get the record (for logging & to delete image if necessary)
    $stmt = $dbh->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($news) {
        // Optional: Delete image file from server
        if (!empty($news['image']) && file_exists('../' . $news['image'])) {
            unlink('../' . $news['image']);
        }

        // Delete record
        $deleteStmt = $dbh->prepare("DELETE FROM users WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            // Log activity
               $action = "deleted vip (ID: $id) on: $current_date";
            log_activity($dbh, $user_id, $action, 'users', $id, $ip_address);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'VIP deleted successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to delete VIP.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'VIP record not found.'];
    }
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid user ID.'];
}

// Redirect back to users listing
header("Location: vip-records");
exit;
