<?php
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Check if this is an API request
if (isset($url[0]) && $url[0] === 'api') {
    $controllerName = 'ApiController';
    // Remove 'api' from the URL parts
    array_shift($url);
    // The next part becomes the action
    $action = isset($url[0]) && $url[0] != '' ? $url[0] : 'products';
    // The rest are parameters
    $params = array_slice($url, 1);
} else {
    $controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
    $params = array_slice($url, 2);
}

if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller not found');
}

require_once 'app/controllers/' . $controllerName . '.php';
$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die('Action not found');
}

call_user_func_array([$controller, $action], $params);
?>