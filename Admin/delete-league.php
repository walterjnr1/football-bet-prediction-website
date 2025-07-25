<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $dbh->prepare("SELECT * FROM leagues WHERE id = ?");
    $stmt->execute([$id]);
    $leagues = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($leagues) {
       
        // Delete record
        $deleteStmt = $dbh->prepare("DELETE FROM leagues WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            // Log activity
               $action = "deleted leagues on: $current_date";
            log_activity($dbh, $user_id, $action, 'leagues', $id, $ip_address);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Leage deleted successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to delete League.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'leagues record not found.'];
    }
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid leagues ID.'];
}

// Redirect back to leagues listing
header("Location: league-record");
exit;
