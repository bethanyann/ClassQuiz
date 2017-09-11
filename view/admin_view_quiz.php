<?php
//get the quiz out of the session
$quiz = unserialize($_SESSION['quiz']);

//use this as the limit for the loop
$totalNumQuestions = $quiz->numberOfQuestions;

$questionList = $quiz->questionList; //put the questions in their own array variable

?> 

<!DOCTYPE html>
<!--
This view will iterate through one quiz object and display it back to the user, giving them the option of deleting or 
editing the quiz
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Quiz View</title>
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
                <div class="col-sm-8">
                    <!--content goes here -->                         
                    <div class="col-sm-11"  style="padding:0px;"> 
                        <h3 class="text-center" style="padding-bottom:15px;">Quiz for <?php echo $_SESSION['courseName'] ?></h3>
                        <h4 class="text-center">Chapter <?php echo $quiz->quizChapterNumber ?></h4>
                        <h4 class="text-center">Total number of quiz questions: <?php echo $totalNumQuestions?></h4>
                        <form class="form-group" action="quiz_controller.php" method="POST">
                            <div class="row text-center" style="padding-bottom:20px;" >
                                <button type="submit" class="btn btn-primary" name="action" value="editQuiz" disabled>Edit Quiz &raquo;</button>
                                <button type="submit" class="btn btn-primary" name="action" value="deleteQuiz" style="margin-left:5px;">Delete Quiz &raquo;</button>
                            </div>
                            <?php for ($x = 0; $x < $totalNumQuestions; $x++) : ?>                      
                                <table class="table table-responsive">
                                    <tr style="border-bottom: #ccccff 5px solid;background-color:#eeeeff;">
                                        <td class="col-sm-4"><label class="pull-right">Question # <?php echo ($x + 1) ?>:</label></td>
                                        <td><span value=""><?php echo $questionList[$x]->questionText ?></span></td>
                                    </tr>   
                                   
                                    <?php for ($y = 0; $y < count($questionList[$x]->answers); $y++) : ?>
                                       <?php $answerValue = $questionList[$x]->answers[$y]->isCorrect;    
                                             if($answerValue == 1) :?>
                                            <tr>
                                                <td class="col-sm-4"><label class="pull-right">Correct Answer:</label></td>
                                                <td><span value=""><?php echo $questionList[$x]->answers[$y]->answerText ?></span></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td class="col-sm-4"><label class="pull-right">Incorrect Answer #<?php echo($y) ?>: </label></td>
                                                <td><span value=""><?php echo $questionList[$x]->answers[$y]->answerText ?></span></td>
                                            </tr>
                                        <?php endif; ?>                             
                                    <?php endfor; ?>
                                    <tr>
                                        <td class="col-sm-4"><label class="pull-right">Correct Answer Page #:</label></td>
                                        <td><span value=""><?php echo $questionList[$x]->correctAnswerPageNumber ?></span></td>    
                                    </tr>
                                </table>   
                            <?php endfor; ?>   
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

