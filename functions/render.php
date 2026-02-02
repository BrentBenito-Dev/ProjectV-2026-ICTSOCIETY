<?php
function renderView($folder, $viewName, $data = []) {
    // 1. Transform array keys into variables
    extract($data);

    // 2. Define the Base Path (Points to "Project Folder")
    $basePath = dirname(__DIR__); 

    // 3. Define the paths based on your structure
    $childView = $basePath . "/layouts/{$folder}/{$viewName}.php";
    
    // Choose layout based on folder (client vs server)
    $layoutType = ($folder === 'server') ? 'layout_admin' : 'layout_client';
    $layoutPath = $basePath . "/layouts/main-layout/{$layoutType}.php";

    // 4. Verification and Rendering
    if (file_exists($childView) && file_exists($layoutPath)) {
        include $layoutPath;
    } else {
        echo "<pre>Error: File not found.\nLooking for:\nView: $childView\nLayout: $layoutPath</pre>";
    }
}
?>