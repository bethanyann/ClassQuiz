<?php
//make sure admin is signed in
$userType = $_SESSION['userType'];
if ($userType != 'admin') { //this works!!!!!!
    header("Location: ../index.php");
    die; //redirect user to home page if they are not admin
}
//this forces user to go through the admin login page before they can view this page
if (!$adminCourses) {
    header("Location: ../index.php");
    die;
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
    <?php include'../shared/navigation.php';?>
    <div class="container-fluid">
      
        <div class="row">
            <?php include '../shared/admin_navigation.php'; ?>
            <h2 class="text-center">Administrator Dashboard</h2>
            <div class="col-sm-9" style="padding-left:20px;">
                <h3>Current Courses</h3>         
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th class="text-center">Course #</th>
                        <th class="text-center">Course Name</th>      
                    </tr>
                    <?php foreach ($adminCourses as $course) : ?>                       
                        <tr class="table-hover"> 
                            <td class="courseID"><?php echo $course['courseID'] ?></td>
                            <td class="courseName"><?php echo $course['courseName'] ?></td>
                            <td>
                                <form action="admin_controller.php" metod="POST">
                                    <button type="submit" class="btn-link" name="action" value="viewQuizzes">View Quizzes</button>
                                    <input type="hidden" name="courseID" value="<?php echo $course['courseID'] ?>">
                                    <input type="hidden" name="courseName" value="<?php echo $course['courseName'] ?>">
                                </form>
                            </td>
                            <td>
                                <form action="admin_controller.php" metod="POST">
                                    <button type="submit" class="btn-link" name="action" value="manageStudents">Course Students</button>
                                    <input type="hidden" name="courseID" value="<?php echo $course['courseID'] ?>">
                                    <input type="hidden" name="courseName" value="<?php echo $course['courseName'] ?>">
                                </form>
                            </td>
                            <td>
                                <form action="admin_controller.php" metod="POST">
                                    <button type="submit" class="btn btn-primary" name="action" value="deleteCourse">Delete Course</button>
                                    <input type="hidden" name="courseID" value="<?php echo $course['courseID'] ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>       
            <div class="col-sm-5" style="padding-left:20px;">
                <!--ADD A NEW COURSE--><h3>Add a new course</h3>
                <form class="form-group" action="admin_controller.php" method="POST"> <!-- adding a new course to db-->
                    <table class="table table-responsive ">
                        <tr class="form-group">
                            <td class="col-sm-4 text-left"><label for="">Course Name: </label></td>
                            <td class="col-sm-1 text-right"><input class="form-control" type="text"  name="courseName" id="course_name" style="width:150px;" required></td>
                            <td><span class="error text-danger" id="numQuestionError">*</span></td>
                        </tr>
                        <tr class="form-group ">
                            <td class="col-sm-4 text-left"><label for="">Course Number: </label></td>          
                            <td class="col-sm-1 text-right"><input class="form-control" type="text" name="courseNumber" id="course_num" style="width:150px;" required>
                            <td><span class="error text-danger" id="chapterError" value="<?php if (isset($error_message)) { echo $error_message; } ?>">*</span></td>
                        </tr>
                        <tr class="form-group">
                            <td><input type="hidden" name="action" value="addCourse"</td>
                            <td><button type="submit" class="btn btn-primary" name="action" value="addCourse">Add New Course &raquo;</button><td>
                        </tr>
                    </table>
                </form>
            </div>
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
