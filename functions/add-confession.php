<?php

    require_once 'database.php';
function saveConfession($data) {
    $pdo = getDB();
    
    $sql = "INSERT INTO tbl_confessions (Confess_Name, Section, Custom_Clue,  Message, Recipient_Name, Tries, Authenticity) 
            VALUES (:Confess_Name, :Section, :Custom_Clue, :Message, :Recipient_Name, :Tries, :Authenticity)";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind the mapped data
    return $stmt->execute([
        ':Confess_Name'   => $data['Confess_Name'],
        ':Section'        => $data['Section'],
        ':Custom_Clue'    => $data['Custom_Clue'],   
        ':Message'        => $data['Message'],
        ':Recipient_Name' => $data['Recipient_Name'],
        ':Tries'          => 0,
        ':Authenticity'   => 1
    ]);
}

?>