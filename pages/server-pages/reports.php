<?php
    require_once '../../functions/admin-functions.php';
    require_once '../../functions/render.php';

    // Handle AJAX requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'create') {
            $result = createConfessionOrder($_POST['ticket_number'], $_POST['section']);
        } elseif ($action === 'update') {
            $result = updateConfessionOrder($_POST['order_id'], $_POST['ticket_number'], $_POST['section']);
        } elseif ($action === 'delete') {
            $result = deleteConfessionOrder($_POST['order_id']);
        } else {
            $result = ['type' => 'error', 'message' => 'Invalid action.'];
        }
        echo json_encode($result);
        exit;
    }

    if (isset($_GET['action']) && $_GET['action'] === 'get_orders') {
        $orders = getConfessionOrders();
        echo json_encode($orders);
        exit;
    }
    // Calculate metrics
    $totalSales = getTotalSales();
    $avgTries = getAvgTries();
    $orders = getConfessionOrders();

    // Include renderView function if not already included

    // Render the reports page
    renderView('server', 'reports', [
        'title' => 'Reports',
        'heroText' => 'View Sales and Tries Metrics',
        'totalSales' => $totalSales,
        'avgTries' => $avgTries,
        'orders' => $orders
    ]);
?>

<script src="../../assets/javascript/reports.js"></script>