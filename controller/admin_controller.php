<?php
// *****ADMINISTRATOR MANAGEMENT CONTROLLER *****
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../database.php'); 
include '../classes/Quiz.php';
include_once '../shared/navigation.php';

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'login';
    }     
}

//$user_list = get_all_usernames();
//$_SESSION['user_list'] = $user_list;

switch($action){
    case 'login_nav'://this action will be sent from the login controller
        //this adminID will come from the Session['LoggedInUser'] when log in functionality is implemented
        $adminID = $_SESSION['userID'];
        //get admin classes & save in a list
        $adminCourses = GetAdminCourses($adminID);        
        //go to admin page
        include '../view/admin_dashboard.php';
        break;
    case 'adminDashboard' : 
        //if user logged in is an admin   
        $adminCourses = GetAdminCourses($adminID);
        include '../view/admin_dashboard.php';
    case 'viewQuizzes':  
        $listOfQuizzes = null; //to hopefully prevent the quizzes from accumulating in this list
        
        $courseName = filter_var($_REQUEST['courseName']);  //figure out a different way to do this later
        $courseID = filter_var($_REQUEST['courseID']); //figure out a different way to do this later
        //when admin selects a specfic class, this will pull up all associated quizzes
        
        $listOfQuizzes = GetCourseQuizzes($courseID);
        
        //saving the selected course information into a session to be accessed throughout the new quiz process
        $_SESSION['courseName'] = $courseName;
        $_SESSION['courseID'] = $courseID;
        //send to admin dashboard quiz view page.
        include '../view/admin_quiz_list.php';
        break; 
    case 'manageStudents':
        //have this redirect to another controller
        break;
    case 'addCourse' :
        $cname = filter_input(INPUT_POST,'courseName');
        $cnum = filter_input(INPUT_POST, 'courseNumber');
        
        break;
    default:
        break;
}