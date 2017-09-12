<?php

// this is going to be a controller

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
        header("Location: view/login.php");
        break;
    case'logout' :
        $_SESSION['usernameLoggedIn']=NULL;
        include 'view/home.php';
        die();
    case 'none':
        $_SESSION['usernameLoggedIn']=NULL;
        include 'view/home.php';
        break;
//    case 'admin':
//        include 'admin_controller.php';
//        break;
    default:
        break;
}
?>

