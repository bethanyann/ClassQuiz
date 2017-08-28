<?php
//get the quiz out of the session
$quiz = unserialize($_SESSION['quiz']);

//use this as the limit for the loop
$totalNumQuestions = $_SESSION['numQuestions'];


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
                <div class="col-sm-7">
                    <!--content goes here --> 
                    <h3 class="text-center" style="padding-bottom:15px;">Confirm new quiz for <?php echo $_SESSION['courseName'] ?></h3>
                    <h4 class="text-center">Quiz is for Chapter <?php echo $quiz->quizChapterNumber ?></h4>
                    <h4 class="text-center">Total number of quiz questions: <?php echo $totalNumQuestions?></h4>              
                    <div class="col-sm-11"  > 
                        <form class="form-group" action="quiz_controller.php" method="POST">
                            <?php for ($x = 0; $x < $totalNumQuestions; $x++) : ?>                      
                                <table class="table table-responsive">
                                    <tr>
                                        <td class="col-sm-4"><label>Question # <?php echo ($x + 1) ?>:</label></td>
                                        <td><span value=""><?php echo $questionList[$x]->questionText ?></span></td>
                                    </tr>   
                                    <!--$answerList = $question->answers; -->
                                    <?php for ($y = 0; $y < count($question->answers); $y++) : ?>
                                        <!--print_r ($questionList[0]->answers[0]->answerText);-->
                                        <?php if ($answers[$y]->isCorrect === 1): ?>
                                            <tr>
                                                <td class="col-sm-4"><label>Correct Answer:</label></td>
                                                <td><span value=""><?php echo $answers[$y]->answerText ?></span></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td class="col-sm-4"><label>Incorrect Answer #<?php echo($y + 1) ?>: </label></td>
                                                <td><span value=""><?php echo $answers[$y]->answerText ?></span></td>
                                            </tr>
                                        <?php endif; ?>                             
                                    <?php endfor; ?>
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

