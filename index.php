<?php
session_start();
date_default_timezone_set("Asia/Ho_Chi_Minh");

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$controller = ucfirst($controller);
$controller .= "Controller";

$path_controller = "controllers/$controller.php";

if (file_exists($path_controller) == false) {
    header('Location: index.php?controller=home&action=page404');
    die();
}

require_once "$path_controller";

$object = new $controller();

if (method_exists($object, $action) == false) {
    header('Location: index.php?controller=home&action=page404');
    die();
}

$object->$action();