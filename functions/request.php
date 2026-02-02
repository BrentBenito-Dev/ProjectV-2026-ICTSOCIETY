<?php 
// This function group handles HTTP Requests
function input($key, $default = null) {
    // Check POST first, then GET
    $value = $_POST[$key] ?? $_GET[$key] ?? $default;

    if ($value !== null && is_string($value)) {
        // Remove accidental whitespace and escape HTML characters
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    return $value;
}
?>