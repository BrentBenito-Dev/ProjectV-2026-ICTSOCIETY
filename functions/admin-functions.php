<?php
/**
 * Admin Pages Function Group
 * Note: Categorize Functions
 */


    require_once 'database.php';

// Confessions
function saveConfession($data) {
    $pdo = getDB();
    
    $sql = "INSERT INTO tbl_confessions (Confess_Name, Custom_Clue, Message, Recipient_Name) 
            VALUES (:Confess_Name, :Custom_Clue, :Message, :Recipient_Name)";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind the mapped data
    return $stmt->execute([
        ':Confess_Name'   => $data['Confess_Name'],
        ':Custom_Clue'    => $data['Custom_Clue'],
        ':Message'        => $data['Message'],
        ':Recipient_Name' => $data['Recipient_Name']
    ]);
}


?>