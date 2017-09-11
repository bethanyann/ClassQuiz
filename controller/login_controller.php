<?php
/* Class Quiz */
/*CONTROLLER - will contain the login stuff */
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
if(!isset($_SESSION)){session_start();}

//including a new database file for just login stuff
require_once('../database_user.php');
require_once('../classes/ValidationClass.php');
//instantiate validation class 
$validate = new ValidationClass();

if(!isset($_SESSION['userNameLoggedIn'])){  $loggedInStatus = 0; }
else{  $loggedInStatus = 1; }

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'login_nav';
    }
}

switch($action){  
    case 'login_nav':
        //get username and password from the nav bar
        $un = filter_input(INPUT_POST, "username");
        $pw = filter_input(INPUT_POST, "password");
        $username = htmlspecialchars($un);
        $password = htmlspecialchars($pw);
        
        //ok now see if its an admin or a student
        $isAdmin = checkUserType($username);
        if($isAdmin) //if = true the user is an admin
        {   //database call to get user's info
            $admin = getUserInfo($username,$password);  
            if($admin)
            {
                //if its admin, set $_SESSION['loggedInUser'] = admin; 
                //redirect to admin dashboard
                //set logged in status = 1;
                $_SESSION['userType'] = $admin[0]['userType'];
                $_SESSION['usernameLoggedIn'] = $admin[0]['adminUsername'];
                $_SESSION['userID'] = $admin[0]['adminID'];
             
                $action = NULL;
                include '../controller/admin_controller.php';
                break;
            }
            else //the user info could not be retrieved
            {
                $error_message = "Your password is incorrect";
                $_SESSION['username'] = $admin[0]['adminUsername'];
                
                $_SESSION['usernameLoggedIn'] = null;
                include'../view/login.php';
            }  
        }
        else
        {
            //user is a student, so do student things
        }
        
        
        break;
     case 'userIsLoggedIn':
       
        break;  
    case 'login_page':
        $loggedInStatus = 0;
        include 'login.php';
        break;
    case 'logout': //hopefully get this working? $login_status = 0; 
        session_destroy();
        $loggedInStatus = 0;
        include 'login.php';
        break;
    case 'login': //coming from the login page
        //session_destroy();
        $username = filter_input(INPUT_POST, "username");
        $password = filter_input(INPUT_POST, "password");

        if($validate->isValuePresent($username, "username") && $validate->isUsernameFormatAllowed($username, "username"))
        {
            if($validate->isValuePresent($password, "password") && $validate->isPasswordFormatAllowed($password, "password"))
            {
                //if everything passes validation then 
                $password_filtered = htmlspecialchars($password);
                $user_name_filtered = htmlspecialchars($username);
                //check to see if username exsists then check to see if password matches
               
                $user_exists = check_username_exists($user_name_filtered);
                if($user_exists) //if the user does exist in the database
                {  
                    //puts user info in array $user
                    $user = get_user($user_name_filtered);
                    $stored_password = $user[0]['Password'];
                    
                   if(password_verify($password_filtered, $stored_password))
                   { //if username and password match, create session and go to the "profile" page. 
                        $_SESSION['userNameLoggedIn'] = $user[0]; //name of the session array
                        $name = $_SESSION['userNameLoggedIn']['Name'];
                        $username = $_SESSION['userNameLoggedIn']['Username'];
                        $email = $_SESSION['userNameLoggedIn']['Email'];
                        $dateofbirth = $_SESSION['userNameLoggedIn']['DOB'];
                        
                        $loggedInStatus = 1; //come back here and fix this stuff and things later maybe
                        include('profile.php');
                        exit();
                    }
                    else //if the password does not match the username
                    {
                        $login_error = "That seems to be the wrong password. Try, try again. "; 
                        include('login.php');
                        exit();
                    }
                }
                else //if the username does not exsist in the database
                {
                    $login_error = "That username does not exist. Try logging in again.";
                    include('login.php');
                    exit();
                }
        }
        else{ //if password does not pass validation
           $error_message = $validate->getErrorMessage();
           include('login.php');
           exit();
        }
    }
    else{ //if username does not pass validation 
       $error_message = $validate->getErrorMessage();
       include('login.php');
       exit();
    }
    break;  
  
   // case 'createAdmin' :
   //     $username = "admin";
   //    $password = 'admin';
        
   //    $hash = password_hash($password, PASSWORD_BCRYPT);
   //     admin_insert($username, $hash);
        
   //     $yes = password_verify('admin',$hash);
   //     break;
}//end of switch