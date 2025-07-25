<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $dbh->prepare("SELECT * FROM teams WHERE id = ?");
    $stmt->execute([$id]);
    $teams = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($teams) {
        

        // Delete record
        $deleteStmt = $dbh->prepare("DELETE FROM teams WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            // Log activity
               $action = "deleted team (ID: $id) on: $current_date";
            log_activity($dbh, $user_id, $action, 'teams', $id, $ip_address);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Team deleted successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to delete Team.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Team record not found.'];
    }
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid Team ID.'];
}

// Redirect back to teams listing
header("Location: team-record");
exit;
