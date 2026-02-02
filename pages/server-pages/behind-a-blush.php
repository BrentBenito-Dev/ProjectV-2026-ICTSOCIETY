<?php
// Note the path: functions/render.php
require_once '../../functions/render.php';

renderView('server', 'behind-a-blush', [
    'title' => 'Behind a blush',
    'heroText' => 'Welcome to the Server Side'
]);

?>