<?php
    require_once '../../functions/render.php';
    require_once '../../functions/confessions-dashboard-functions.php';


// Handle AJAX request to increment tries
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'increment_tries') {
    $result = incrementTries($_POST['confession_id']);
    echo json_encode($result);
    exit;
}
$confessions = displayConfessions();

// Call the render function to display the view
renderView('client', 'confessions-dashboard', ['confessions' => $confessions]);

?>

<script src="../../assets/javascript/conffessions-dashboard-functions.js"></script>