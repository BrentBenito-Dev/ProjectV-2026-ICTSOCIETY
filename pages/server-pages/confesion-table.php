<?php
    require_once '../../functions/admin-functions.php';
    require_once '../../functions/render.php';

    $data = getConfessions();

    renderView('server', 'confession-table', [
        'title' => 'Add a Confession',
        'heroText' => 'Welcome to the Server Side',
        'data' => $data
    ]);
    
?>