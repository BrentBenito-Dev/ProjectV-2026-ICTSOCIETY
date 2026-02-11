<?php
function incrementTries($confessionId) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("UPDATE tbl_confessions SET Tries = Tries + 1 WHERE id = ?");
        $stmt->execute([$confessionId]);
        return ['type' => 'success'];
    } catch (Exception $e) {
        return ['type' => 'error', 'message' => $e->getMessage()];
    }
}



?>