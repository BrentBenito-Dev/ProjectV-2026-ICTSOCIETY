<?php
    require_once '../../functions/render.php';

    renderView('server', 'add-contestant', [
        'title' => 'Add a Contestant',
        'heroText' => 'Welcome to the Server Side'
    ]);

    // Collect Form Data
    require_once '../../functions/request.php';
    require_once '../../functions/admin-functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Map Form to DB Schema
        $db_data = [
            'Contestant_Name'   => input('first_name') . ' ' . input('last_name'),
            'Section'           => input('section'),
            'Vote_Count'        => input('vote_count')
        ];

        // 2. Save it
        if (addContestant($db_data)) {
            $statusMessage = "Success! Contestant " . $db_data['Contestant_Name'] . " saved.";
        }
    }

?>