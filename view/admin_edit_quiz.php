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

//all of these work, leaving them here and commented out for reference sake
//print_r($quiz->questionList[0]); //this worked! and printed the first question object
//print_r($quiz->questionList[0]->answers[0]->answerText);
//print_r($quiz->questionList[0]->answers[1]->answerText);
//print_r($quiz->questionList[0]->answers[2]->answerText);
//print_r($quiz->questionList[0]->answers[3]->answerText);
//print_r($quiz->questionList[0]->questionText); //working
//set the counter variable
$questionList = $quiz->questionList;
print_r ($questionList[0]->answers[0]->answerText); //printed correct answer text
print_r ($questionList[0]->questionText); //this does work! printed first question text
print_r (count($questionList[0]->answers)); //does this work? yes it gets count of 4
?> 

<!DOCTYPE html>
<!--
This view will allow the admin to edit a quiz. copied this text from the save question page
to use as reference/starting point
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
                    <h2>Confirm new quiz for <?php echo $_SESSION['courseName'] ?></h2>
                    <!--put info about the quiz here like num questions etc-->
                    <?php for($x = 0; $x < $totalNumQuestions; $x++) :  ?>
                                              
                    <table class="table table-responsive col-sm-6">
                        <tr>
                            <td class="col-sm-4"><label>Question:</label></td>
                            <td><input type="text" name="question<?php echo $x ?>" class="form-control" value="<?php echo $questionList[$x]->questionText ?>"></td>
                        </tr>   <!-- ok that errors out on the line above this comment saying that the object isnt a string. getting closer!!!-->
                        <!--$answerList = $question->answers; -->
                       
                        <?php for ($y = 0; $y < count($question->answers); $y++) : ?>
                        
                        <!--print_r ($questionList[0]->answers[0]->answerText);-->
                            <?php if ($answers[$y]->isCorrect === 1): ?>
                                <tr>
                                    <td class="col-sm-4"><label>Correct Answer:</label></td>
                                    <td><input type="text" name="correct_answer<?php echo $y ?>" class="form-control" value="<?php echo $answers[$y]->answerText ?>"></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td class="col-sm-4"><label>Incorrect Answer #: </label></td>
                                    <td><input type="text" name="incorrect_answer<?php echo $y ?>" class="form-control" value="<?php echo $answers[$y]->answerText ?>"></td>
                                </tr>
                            <?php endif; ?>                             
                        <?php endfor; ?>
                        <!--i deleted stuff fom here-->
                    </table>   

                    <?php  endfor; ?>          
                </div>
            </div>
        </div>
    </body>
</html>

