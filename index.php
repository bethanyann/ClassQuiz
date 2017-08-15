<?php
// this is going to be a controller
include 'database.php';
session_start();

$clicked_user = filter_input(INPUT_GET, 'user');
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'none';
    }
}

if (!isset($_SESSION['userNameLoggedIn'])){ $loggedInStatus = 0;}
else{ $loggedInStatus = 1;}

switch ($action){
    case 'none':
        include 'home.php';
        break;
    case 'admin':
        include 'admin_controller.php';
        break;
    default:
        break;
}
?>

