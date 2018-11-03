<?php
define('URL', "http://" . $_SERVER['HTTP_HOST']);
require __DIR__ . '/vendor/autoload.php';
$url_controller = null;
$url_action     = null;
if (isset($_GET['url']) && $_GET['url'] != 'home/index') {
    $url = rtrim($_GET['url'], '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $url_controller = (isset($url[0]) ? $url[0] : null);
    $url_action = (isset($url[1]) ? $url[1] : null);
}
if (file_exists('app/Controllers/' . $url_controller . '.php')) {
    $controller = "Todolist\Controllers\\".ucfirst($url_controller);
    $url_controller = new $controller;
    if (method_exists($url_controller, $url_action)) {
        $url_controller->{$url_action}();
    } else {
        $url_controller->index();
    }
} else {
    $home = new Todolist\Controllers\Home;
    $home->index();
}