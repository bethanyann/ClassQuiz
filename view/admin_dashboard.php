<?php
$loggedInStatus = 0;
//make sure admin is signed in
//$username = $_SESSION['username'];
//if($username != 'admin') //this works!!!!!!
//{
//    header( "Location: home.php" ); die; //redirect user to home page if they are not admin
//} 
//get the admin account info

//this forces user to go through the admin login page before they can view this page
if(!$adminCourses) 
{
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<!-- THIS IS THE ADMIN HOME PAGE FOR THE CLASS QUIZ site --> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Administrator Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/classquiz_main.css">
    <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>

<body>
    <?php include '../shared/navigation.php'; ?>
    <div class="container-fluid">
        <!-- Example row of columns -->
        <div class="row">
            <?php include '../shared/admin_navigation.php'; ?>
            <h2 class="text-center">Administrator Dashboard</h2>
            <div class="col-sm-7">
                <h3>Current Courses</h3>         
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th class="text-center">Course Number</th>
                        <th class="text-center">Course Name</th>
                        <th></th>
                    </tr>
                    <?php foreach ($adminCourses as $course) : ?>                       
                        <tr class="table-hover"> 
                            <td class="courseID"><?php echo $course['courseID'] ?></td>
                            <td class="courseName"><?php echo $course['courseName'] ?></td>
                            <td>
                                <form action="admin_controller.php" metod="POST">
                                    <button type="submit" class="btn-link" name="action" value="viewQuizzes">View Course Quizzes</button>
                                    <input type="hidden" name="courseID" value="<?php echo $course['courseID'] ?>">
                                    <input type="hidden" name="courseName" value="<?php echo $course['courseName'] ?>">
                                </form>
                            </td>
                            <td>
                                <form action="admin_controller.php" metod="POST">
                                    <button type="submit" class="btn-link" name="action" value="manageStudents">View Course Participants</button>
                                    <input type="hidden" name="courseID" value="<?php echo $course['courseID'] ?>">
                                    <input type="hidden" name="courseName" value="<?php echo $course['courseName'] ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>       
        </div>
        <footer>
            <p class="text-center">&copy; capstone project 2017</p>
        </footer>
    </div> <!-- /container -->    

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.min.js"><\/script>');</script>
    <script src="../scripts/vendor/bootstrap.min.js"></script>
    <script src="../scripts/classquiz.js"></script>

</body>
</html>
