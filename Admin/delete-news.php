<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Get the record (for logging & to delete image if necessary)
    $stmt = $dbh->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($news) {
        // Optional: Delete image file from server
        if (!empty($news['image']) && file_exists('../' . $news['image'])) {
            unlink('../' . $news['image']);
        }

        // Delete record
        $deleteStmt = $dbh->prepare("DELETE FROM news WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            // Log activity
               $action = "deleted News (ID: $id) on: $current_date";
            log_activity($dbh, $user_id, $action, 'news', $id, $ip_address);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'News deleted successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to delete news.'];
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'News record not found.'];
    }
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid news ID.'];
}

// Redirect back to news listing
header("Location: news-record");
exit;
