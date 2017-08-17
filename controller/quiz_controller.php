<?php
// *****QUIZ CREATION CONTROLLER *****
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
session_start();

require_once('../database.php'); 
include '../classes/Quiz.php';
include '../classes/Question.php';
include '../classes/Answer.php';

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'createNewQuiz';
    }
}

//global "count" variable that will control the quiz creation and question-adding loop
//$quizQuestionCount
switch ($action){
    case 'createNewQuiz': // this action is coming in from the admin_quiz_list page 
                          //after an admin says they wanna create a new quiz
        //get the course information out of the session 
        $_SESSION['courseName'] = $courseName;
        $_SESSION['courseID'] = $courseID;
        
        //get the quiz information from the form
        $chapterNumber = filter_input(INPUT_POST, 'chapterNum');
        $numberQuestions = filter_input(INPUT_POST, 'numQuestions');
        
        $chapterNum = htmlspecialchars($chapterNumber);
        $numQuestions = htmlspecialchars($numberQuestions);
        
        //make a new quiz object
        $quiz = new Quiz;
        $quiz->numberOfQuestions = $numQuestions;
        $quiz->quizChapterNumber = $chapterNum;
        $quiz->quizClassNumber = $_SESSION['courseName'];
        
        //store the new quiz in a session?
        $_SESSION['quiz'] = $quiz;
        //store the question numbers in the session        
        $_SESSION['numQuestions'] = $numQuestions;
        
        //set $quizQuestionCount = $numQuestions
        //should I store that in a variable or a session? 
        //might be better in a session that way I can use it in the 
        //quiz question view to show Question # out of #
        include '../view/admin_create_quiz.php';
        break;
    
    case 'saveQuestion' : 
        //IF($quizQuestionCount <= $numQuestions {
        //each time the user wants to save a question, i need to grab the data and 
        //store each question and answer object in the 'quiz' class
        
        //OR. grab the answers first, save those in an array of answers. 
        //then make a question object and get the question
        //stuff the answer array into the question object
        //$question->$answerArray[x].answerText ... etc
        
        //then save the question to the quiz question array
        //$quiz->$questionArray[x] = $
        
        $q = filter_input(INPUT_POST, 'question');
        $ca = filter_input(INPUT_POST, 'correct_answer');   
        $ia1 = filter_input(INPUT_POST, 'incorrect_answer1');
        $ia2 = filter_input(INPUT_POST, 'incorrect_answer2');
        $ia3 = filter_input(INPUT_POST, 'incorrect_answer3');
        
        //dont forget to make a thing for what page # the correct answer can be found on. 
        
        $questionType = filter_var(INPUT_POST, 'questionType');
        
        //need a quiz object in the database before saving a question
        
        //after everything is saved, 
        //$quizQuestionCount--
        //
        //}
        //else { if the question limit has been reached, save everything to database}
        // and redirect back to the admin_quiz_list page to see the quiz
        //then figure out a way for the admin to view the quiz.
        //json string?
        //loop on the view? 
        break;
    case 'editQuiz':
        break;
    default:
        break;
}
    


