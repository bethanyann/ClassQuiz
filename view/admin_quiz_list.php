<?php
$loggedInStatus = 0;
//make sure admin is signed in
//$username = $_SESSION['username'];
//if($username != 'admin') //this works!!!!!!
//{
//    header( "Location: home.php" ); die; //redirect user to home page if they are not admin
//} 
//get the admin account info
//get admin list of courses
?>
<!DOCTYPE html>
<!-- THIS DISPLAYS THE QUIZZES ATTACHED TO A SPECFIC QUIZ  --> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>View Course Quizzes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/classquiz_main.css">
    <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>

<body>
    <?php include '../shared/navigation.php'; ?>
    <div class="container-fluid" style="min-height: 400px"> 
        <?php include '../shared/admin_navigation.php'; ?>

        <?php if (!$listOfQuizzes): ?>
            <div class="row">
                <div class="col-sm-7">
                    <h2> <?php echo $_SESSION['courseName'] ?></h2>
                    <br>
                    <h3>This class doesn't have any quizzes set up yet.</h3>
                    <br>
                    <h4> Set up a new quiz:</h4> <br>
                    <form class="form-group" action="quiz_controller.php" method="POST">
                        <table class="table table-responsive ">
                            <tr class="form-group">
                                <td class="col-sm-4 text-left"><label for="numQuestions">Number of questions on the quiz: </label></td>
                                <td class="col-sm-1 text-right"><input class="form-control" type="text"  name="numQuestions" style="width:150px;" required></td>
                                <td><span class="error">*</span></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-sm-4 text-left"><label for="chapterNum">Quiz Chapter Number: </label></td>          
                                <td class="col-sm-1 text-right"><input class="form-control" type="text" name="chapterNum" style="width:150px;" required></td>
                                <td><span class="error">*</span></td>
                            </tr>
                            <tr class="form-group">
                                <td><input type="hidden" name="action" value="createNewQuiz"</td>
                                <td><button type="submit" class="btn btn-primary" name="action" value="createNewQuiz">Build New Quiz &raquo;</button><td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-sm-7">
                    <h2> <?php echo $_SESSION['courseName'] ?></h2>
                   
                        <table class="table table-responsive table-bordered">
                            <tr>
                                <th class="text-center">Quiz Chapter</th>
                                <th class="text-center">Number of Questions</th>
                            </tr>
                            <?php foreach ($listOfQuizzes as $quiz) : ?>                       
                                <tr class="table-hover"> 
                                    <td class="courseID"><?php echo htmlspecialchars($quiz['quizChapterNum']) ?></td>
                                    <td class="courseName"><?php echo htmlspecialchars($quiz['quizNumQuestions']) ?></td>
            <!-- this needs updated <td> <button type="submit" class="btn-link" name="action" value="viewQuizzes">View Course Quizzes</button> </td> 
                                    <td> <button type="submit" class="btn-link" name="action" value="manageStudents">View Course Participants</button> </td>                 
                                    <input type="hidden" name="courseID" value="<?php ?>">
                                    <input type="hidden" name="courseName" value="<?php ?>"> -->
                                </tr>
                            <?php endforeach; ?>
                        </table>
                      
                </div> 
            </div>
            <div class="row">
                <h4>Create a New Quiz</h4>
                <form class="form-group" action="quiz_controller.php" method="POST">
                    <table class="table table-responsive ">
                        <tr class="form-group">
                            <td class="col-sm-4 text-left"><label for="numQuestions">Number of questions on the quiz: </label></td>
                            <td class="col-sm-1 text-right"><input class="form-control" type="text"  name="numQuestions" style="width:150px;" required></td>
                            <td><span class="error">*</span></td>
                        </tr>
                        <tr class="form-group ">
                            <td class="col-sm-4 text-left"><label for="chapterNum">Quiz Chapter Number: </label></td>          
                            <td class="col-sm-1 text-right"><input class="form-control" type="text" name="chapterNum" style="width:150px;" required></td>
                            <td><span class="error">*</span></td>
                        </tr>
                        <tr class="form-group">
                            <td><input type="hidden" name="action" value="createNewQuiz"</td>
                        <!--<td><a class="btn btn-primary" href="admin_controller.php?action=createNewQuiz" role="button">Build a New Quiz </a> </td>-->
                            <td><input type="button" class="btn btn-primary" name="submit" value="Build New Quiz &raquo;"></td>
                        </tr> 
                    </table>
                </form>
            </div>
        </div>
    <?php endif; ?> 
</div> <!-- /container -->    
<footer>
    <p class="text-center">&copy; capstone project 2017</p>
</footer>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.min.js"><\/script>');</script>
<script src="../scripts/vendor/bootstrap.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>
