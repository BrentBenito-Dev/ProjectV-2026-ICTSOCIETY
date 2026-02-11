<?php
    require_once '../../vendor/autoload.php';
    require_once '../../functions/admin-functions.php';
    require_once '../../functions/render.php';

    $message = '';
    $messageType = 'info';
    renderView('server', 'upload-document', [
        'title' => 'Add a Confession',
        'heroText' => 'Welcome to the Server Side',
        'message' => $message,
        'messageType' => $messageType
    ]);

    // Handle POST request

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
        $result = processExcelUpload($_FILES['excel_file']);
    }
    $message = $_GET['message'] ?? ''; // Get message from query string (e.g., after redirect from processor)
    $messageType = $_GET['type'] ?? 'info'; // 'success', 'error', or 'info'

   

?>