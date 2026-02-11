<?php
// Note the path: functions/render.php
require_once '../../functions/render.php';

renderView('client', 'add-confession', [
    'title' => 'Confess Now!',
    'heroText' => 'Welcome to the Server Side'
]);




// Collect Form Data
require_once '../../functions/request.php';
require_once '../../functions/add-confession.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Map Form to DB Schema
    $db_data = [
        'Confess_Name'   => input('first_name') . ' ' . input('last_name'),
        'Section'    => input('section'),
        'Custom_Clue'    => input('custom_clue'),
        'Message'        => input('message'),
        'Recipient_Name' => input('recipient_name')
    ];

    // 2. Save it
    if (saveConfession($db_data)) {
        $statusMessage = "Success! Confession for " . $db_data['Confess_Name'] . " saved.";
    }
}

?>