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
<!-- THIS DISPLAYS THE QUIZZES ATTACHED TO A SPECFIC COURSE  --> 
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

<body height="100">
    <?php include '../shared/navigation.php'; ?>
    <div class="container-fluid" style="padding-left:0px; min-height: 400px"> 
        <?php include '../shared/admin_navigation.php'; ?>
    <!-- IF THERE ARE NO QUIZZES FOR THE SPECFIC COURSE -->
        <?php if (!$listOfQuizzes || $listOfQuizzes===null): ?> 
            <div class="row">
                <div class="col-sm-7">   <!--be sure to set a question limit to 20 questions per quiz-->
                    <h2> <?php echo $_SESSION['courseName'] ?></h2>
                    <br>
                    <h3>This class doesn't have any quizzes set up yet.</h3>
                    <br>
                    <h4> Set up a new quiz:</h4> <br>
                    <form class="form-group" action="quiz_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
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
    <!-- IF THERE ARE QUIZZES FOR THE SPECFIC COURSE -->
        <?php else: ?>
            <div class="row">
                <div class="col-sm-7">
                    <h2 class="text-center"> <?php echo $_SESSION['courseName'] ?></h2>
                    <h3>Existing Quizzes : </h3>
                    <form action="quiz_controller.php" method="POST">
                        <table class="table table-responsive">    
                            <?php foreach ($listOfQuizzes as $quiz) : ?>                       
                                <tr class="table-hover"> 
                                    <td>
                                        <button type="submit" class="btn btn-primary"  name="action" value="viewQuiz">
                                            <h5>Chapter <?php echo htmlspecialchars($quiz['quizChapterNum']) ?> Quiz</h5> 
                                            <h5>- - - - - -</h5>
                                            <h6><?php echo htmlspecialchars($quiz['quizNumQuestions']) ?> Questions</h6>
                                            <input type="hidden" name="chapterNumber" value="<?php echo $quiz['quizChapterNum']?>">
                                            <input type="hidden" name="numQuestions" value="<?php echo $quiz['quizNumQuestions']?>">
                                            <input type="hidden" name="quizID" value="<?php echo $quiz['quizID'] ?>">
                                        </button>
                                    </td>      
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </form>
                </div> 
            
                <div class="col-sm-7">
                    <h3> Set up a new quiz:</h3> <br>
                    <form class="form-group" action="quiz_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
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
        
        
        </div> <!-- END OF CONTAINER -->
    <?php endif; ?> 
</div> <!-- /container -->    
<footer>
    <p class="text-center">&copy; capstone project 2017 - bethany ann</p>
</footer>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.min.js"><\/script>');</script>
<script src="../scripts/vendor/bootstrap.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>
