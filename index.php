<?php

// this is going to be a controller
include 'database.php';
include 'database_user.php';
include '../ClassQuiz/shared/navigation.php';
session_start();
session_destroy();


$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'none';
    }
}

if (!isset($_SESSION['userNameLoggedIn'])) {
    $loggedInStatus = 0;
} else {
    $loggedInStatus = 1;
}

switch ($action) {
    case 'login':
        include'view/login.php';
        break;
    case'logout':
        include'controller/login_controller.php?action=logout';
        break;
    case 'none':
        include 'home.php';
        break;
//    case 'admin':
//        include 'admin_controller.php';
//        break;
    default:
        break;
}
?>

