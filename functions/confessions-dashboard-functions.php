<?php
    require_once 'database.php';

function displayConfessions() {
    try {
        // Get PDO connection
        $pdo = getDB();
        
        // Query to fetch the required fields, with Tries before Authenticity
        $stmt = $pdo->prepare("SELECT id, Confess_Name, Custom_Clue, Message, Recipient_Name, Section, Tries, Authenticity FROM tbl_confessions");
        $stmt->execute();
        
        // Fetch all results
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        // Return empty array on error; handle in view if needed
        return [];
    }
}

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