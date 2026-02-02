<?php
// Note the path: functions/render.php
require_once 'functions/render.php';

renderView('client', 'home', [
    'title' => 'My Project Home',
    'heroText' => 'Welcome to the Client Side'
]);

?>