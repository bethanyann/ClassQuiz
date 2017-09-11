<?php
$userType = $_SESSION['userType'];
if ($userType != 'admin') { //this works!!!!!!
    header("Location: ../home.php");
    die; //redirect user to home page if they are not admin
}
//get the quiz out of the session
$quiz = unserialize($_SESSION['quiz']);
//use this as the limit for the loop
$totalNumQuestions = $_SESSION['numQuestions'];
//put the questions list in it's own variable for easy access
$questionList = $quiz->questionList;
?> 
<!DOCTYPE html>
<!--
This view will iterate through the quiz object and display every
quiz with it's answers back to the user in text boxes for the user to look at 
and confirm that it's what they want to save. Implement the ability to edit the quiz at this point.
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Save a New Quiz</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">   
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/classquiz_main.css">
        <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <?php include '../shared/navigation.php'; ?>
        <div class="container-fluid" style="min-height: 400px">
            <!-- Example row of columns -->
            <div class="row">
                <?php include '../shared/admin_navigation.php'; ?>
                <div class="col-sm-9">
                    <!--content goes here --> 
                    <h3 class="text-center" style="padding-bottom:15px;">Confirm new quiz for <?php echo $_SESSION['courseName'] ?></h3>
                    <h4 class="text-center">Quiz is for Chapter <?php echo $quiz->quizChapterNumber ?></h4>
                    <h4 class="text-center">Total number of quiz questions: <?php echo $totalNumQuestions?></h4>              
                    <div class="col-sm-11"  > 
                        <form class="form-group" action="quiz_controller.php" method="POST">
                            <?php for ($x = 0; $x < $totalNumQuestions; $x++) : ?>                      
                                <table class="table table-responsive">
                                    <tr style="border-bottom: #ccccff 5px solid;background-color:#eeeeff;">
                                        <td class="col-sm-4"><label class="pull-right">Question # <?php echo ($x + 1) ?>:</label></td>
                                        <td><span value=""><?php echo $questionList[$x]->questionText ?></span></td>
                                    </tr>   
                                    <!--$answerList = $question->answers; -->
                                    <?php for ($y = 0; $y < count($questionList[$x]->answers); $y++) : ?>
                                       <?php $answerValue = $questionList[$x]->answers[$y]->isCorrect;    
                                             if($answerValue == 1) :?>
                                            <tr>
                                                <td class="col-sm-4"><label class="pull-right">Correct Answer:</label></td>
                                                <td><span value=""><?php echo $questionList[$x]->answers[$y]->answerText ?></span></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td class="col-sm-4"><label class="pull-right">Incorrect Answer #<?php echo($y + 1) ?>: </label></td>
                                                <td><span value=""><?php echo $questionList[$x]->answers[$y]->answerText ?></span></td>
                                            </tr>
                                        <?php endif; ?>                             
                                    <?php endfor; ?>
                                    <tr>
                                         <td class="col-sm-4"><label class="pull-right">Correct Answer Page Number:</label></td>
                                         <td><span value=""><?php echo $questionList[$x]->correctAnswerPageNumber ?></span></td>      
                                    </tr>
                                </table>   
                            <?php endfor; ?>
                            <div class="row text-center">
                                <button type="submit" class="btn btn-primary" name="action" value="editQuiz" disabled>Edit Quiz &raquo;</button>
                                <button type="submit" class="btn btn-primary" name="action" value="saveQuiz" style="margin-left:10px;">Save Quiz &raquo;</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <footer>
                <p class="text-center">&copy; capstone project 2017 - bethany ann</p>
            </footer>
        </div> <!-- end container? -->
    </body>
</html>

