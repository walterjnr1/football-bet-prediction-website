<?php
include('../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prediction_id = $_POST['prediction_id'];
    $result = $_POST['result'];
    $score = $_POST['score'];
    $notes = $_POST['notes'];

    // Fetch match date and type
    $stmt = $dbh->prepare("SELECT match_date, type FROM predictions WHERE id = ?");
    $stmt->execute([$prediction_id]);
    $prediction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prediction) {
        $dbh->beginTransaction();

        try {
            // Update predictions table
            $stmt = $dbh->prepare("UPDATE predictions SET result = ?, score = ? WHERE id = ?");
            $stmt->execute([$result, $score, $prediction_id]);

            // Only insert vip_result if type is 'vip' or 'fixed'
            if (in_array($prediction['type'], ['vip', 'fixed'])) {
                $stmt = $dbh->prepare("INSERT INTO vip_results (result_date, outcome, notes) VALUES (?, ?, ?)");
                $stmt->execute([$prediction['match_date'], $result, $notes]);
            }

            $dbh->commit();
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Prediction Outcome updated successfully.'];
        } catch (Exception $e) {
            $dbh->rollBack();
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to update prediction Outcome: ' . $e->getMessage()];
        }
     } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Prediction not found.'];
        }
}

header("Location: prediction-record");
exit;

