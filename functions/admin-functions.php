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

function addContestant($data){
    $pdo = getDB();

    $sql = "INSERT INTO tbl_contestant (Contestant_Name, Section, Vote_Count) 
            VALUES (:Contestant_Name, :Section, :Vote_Count)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':Contestant_Name'   => $data['Contestant_Name'],
        ':Section'           => $data['Section'],
        ':Vote_Count'        => $data['Vote_Count']
    ]);
}


?>