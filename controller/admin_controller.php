<?php
// *****ADMINISTRATOR MANAGEMENT CONTROLLER *****
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//include'../shared/navigation.php';
require_once('../database.php'); 
include '../classes/Quiz.php';


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
        $adminCourses = getAdminCourses($adminID); 
        $_SESSION['adminCourses'] = $adminCourses;
        //go to admin page
        include '../view/admin_dashboard.php';
        die();   
    case 'adminDashboard' : 
        //if user logged in is an admin   
        $adminID = $_SESSION['userID'];
        $adminCourses = GetAdminCourses($adminID);
        $_SESSION['adminCourses'] = $adminCourses;
        include '../view/admin_dashboard.php';
        die();
    case 'viewQuizzes':  
        
        $listOfQuizzes = null; //to hopefully prevent the quizzes from accumulating in this list
        
        $courseName = filter_var($_REQUEST['courseName']); 
        $courseID = filter_var($_REQUEST['courseID']); 
        
        $listOfQuizzes = GetCourseQuizzes($courseID);
        
        //saving the selected course information into a session to be accessed throughout the new quiz process
        $_SESSION['courseName'] = $courseName;
        $_SESSION['courseID'] = $courseID;
        //send to admin dashboard quiz view page.
        include '../view/admin_quiz_list.php';
        break; 
    case 'addCourse' :
        $cname = filter_input(INPUT_POST,'courseName');
        $cnum = filter_input(INPUT_POST, 'courseNumber');
        //get the course name, course ID number, and admin ID 
        $courseName = htmlspecialchars($cname);
        $courseNumber = htmlspecialchars($cnum);
        $adminID = $_SESSION['userID']; 
        
        //insert the new class into the database
        $success = AddNewCourse($courseName,$courseNumber,$adminID);
        if($success)
        {  //get the new list of courses
            $adminCourses = GetAdminCourses($adminID);
            $action = NULL;
            //go back to admin page
            include '../view/admin_dashboard.php';
        }
        break;
    case 'deleteCourse' :
        $courseID = filter_var($_REQUEST['courseID']);
        $adminID = $_SESSION['userID'];
        //go to the database and delete the course
        $success = DeleteCourse($courseID,$adminID);
        if($success)
        {  //get the new list of courses
            $adminCourses = GetAdminCourses($adminID);
            $action = NULL;
            //go back to admin page
            include '../view/admin_dashboard.php';
        }
        break;
    case 'manageStudents': //get the students associated with a specfic class  
        $courseName = filter_var($_REQUEST['courseName']); 
        $courseID = filter_var($_REQUEST['courseID']); 
        //database call
        $listOfStudents = GetCourseStudents($courseID);
        $listOfAllStudents = GetAllStudentsNotInACertainCourse($courseID);
        
        $_SESSION['courseName']=$courseName;
        $_SESSION['courseID']=$courseID;
        include '../view/admin_manage_students.php';
        break;
    case 'saveNewStudent' : //saves a new user entered student to the db and to a course
        $courseID = $_SESSION['courseID'];
        $stuID = filter_input(INPUT_POST, 'stuID');
        $fName = filter_input(INPUT_POST, 'firstName');
        $lName = filter_input(INPUT_POST, 'lastName');
        
        $studentID=htmlspecialchars($stuID);
        $firstName=htmlspecialchars($fName);
        $lastName=htmlspecialchars($lName);
        
        //insert new student into db
         $success =AddNewStudent($studentID,$firstName,$lastName);
        //add student to the course
        if($success)
        {
            AssignStudentToCourse($studentID,$courseID);
        }
        else
        {
            //panic
        }
        //get new list of students assigned to course
        $listOfStudents = GetCourseStudents($courseID);
        //get list of students not assigned to course
        $listOfAllStudents = GetAllStudentsNotInACertainCourse($courseID);
        include '../view/admin_manage_students.php';
        
        break;
    case 'removeStudent' :
        $courseID = filter_input(INPUT_POST, "courseID");
        $studentID = filter_input(INPUT_POST, "studentID");
        //go to db and remove student from class
        RemoveStudentFromCourse($studentID,$courseID);
        //return a new list of students assigned to the course
        $listOfStudents=GetCourseStudents($courseID);
        //return all of the students not assigned to this course
        $listOfAllStudents=GetAllStudentsNotInACertainCourse($courseID);
        //go back to the calling page
        include '../view/admin_manage_students.php';
        break;
    case 'editStudent' :
        $studentID = filter_input(INPUT_POST, "studentID");
        $studentFirstName = filter_input(INPUT_POST, "studentFName");
        $studentLastName = filter_input(INPUT_POST, "studentLName");
        $_SESSION['studentID']=$studentID;
        include'../view/admin_edit_student.php';    
        break;
    case 'saveExistingStudent' : //saves an existing student to a new course
        $courseID = $_SESSION['courseID'];
        //get selected student from the select box
        $studentID = filter_var($_POST['studentsToAdd']);
        //go to db and assign that student to the course
        AssignStudentToCourse($studentID,$courseID);
        //return a new list of students assigned to the course
        $listOfStudents = GetCourseStudents($courseID);
        //return the students not assigned to this course
        $listOfAllStudents = GetAllStudentsNotInACertainCourse($courseID);
        //go back to the calling page
        include '../view/admin_manage_students.php';
        break;
    case 'saveEditStudent' :
        $studentID = $_SESSION['studentID'];
        $studentFirstName = filter_input(INPUT_POST, 'firstName');
        $studentLastName = filter_input(INPUT_POST, 'lastName');
        
        $success = EditStudent($studentID,$studentFirstName,$studentLastName);
        
        //now go back to the class the student was on? 
        $courseID = $_SESSION['courseID'];
          //return a new list of students assigned to the course
        $listOfStudents = GetCourseStudents($courseID);
        //return the students not assigned to this course
        $listOfAllStudents = GetAllStudentsNotInACertainCourse($courseID);
        //go back to the calling page
        include '../view/admin_manage_students.php';
        break;
    default:
        break;
}