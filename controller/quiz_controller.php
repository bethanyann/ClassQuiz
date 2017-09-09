<?php
// *****QUIZ CREATION CONTROLLER *****
//start session
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
session_start();
//include required classes
require_once('../database.php'); 
include '../classes/Quiz.php';
include '../classes/Question.php';
include '../classes/Answer.php';
//get the action from the POST/GET
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
        if ($action === NULL) {
        $action = 'createNewQuiz';       
    }
}

switch ($action){
   
    case 'createNewQuiz': //coming in from the admin_quiz_list page if a new quiz is created               
        //get the course information out of the session -working
        $courseName = $_SESSION['courseName'];
        $courseID = $_SESSION['courseID'];
        
        //get the quiz information from the form - working
        $chapterNumber = filter_input(INPUT_POST, 'chapterNum');
        $numberQuestions = filter_input(INPUT_POST, 'numQuestions');
        $chapterNum = htmlspecialchars($chapterNumber);
        $numQuestions = htmlspecialchars($numberQuestions);
        
        //make a new quiz object
        $quiz = new Quiz();  
        $quiz->numberOfQuestions = $numQuestions;
        $quiz->quizChapterNumber = $chapterNum;
        $quiz->courseID = $courseID;
        
        //store quiz in SESSION until quiz is ready to be saved
        $_SESSION['quiz'] = serialize($quiz);
      
        //store the question numbers in the session        
        $_SESSION['numQuestions'] = $numQuestions;
        $_SESSION['questionCounter'] = 1;  //set the counter to 1
      
        include '../view/admin_create_quiz.php';
        break;
    case 'saveQuestion': //this action will be hit every time the user wants to save a question/answer set
        $quiz = unserialize($_SESSION['quiz']);
        
        if (isset($quiz)) //if the quiz has been started
        {
            //each time the user wants to save a question, i need to grab the data and 
            //store each question and answer object in the 'quiz' class
            $totalNumQuestions = $_SESSION['numQuestions'];
            $questionCounter = $_SESSION['questionCounter'];       
            //get question type
            $qt = $_POST['questionType'];     
            $questionType = htmlspecialchars($qt);
            //get question text
            $qtext = filter_input(INPUT_POST, 'question'); 
            $questionText = htmlspecialchars($qtext);       
            //get the correct answer page number
            $pn = filter_input(INPUT_POST, 'page_number');
            $pageNumber = htmlspecialchars($pn);
                       
            //now based on question type, grab the answers and put them in the question's answer array
            if($questionType === "multiple_choice") 
            {   
                //make a new array 
                $answers = array();
                //there will be 1 correct answer
                $ca = filter_input(INPUT_POST, 'correct_answer');
                $correctAnswer = htmlspecialchars($ca);
                $c_answer = new Answer(); 
                $c_answer->isCorrect = 1;
                $c_answer->answerText = $correctAnswer;
                $answers[] = $c_answer;
             
                //and 3 incorrect answers
                $ia1 = filter_input(INPUT_POST, 'incorrect_answer1');
                $incorrectAnswer1 = htmlspecialchars($ia1);
                $i1_answer = new Answer();
                $i1_answer->isCorrect = 0;
                $i1_answer->answerText = $incorrectAnswer1;
                $answers[] = $i1_answer;
                
                $ia2 = filter_input(INPUT_POST, 'incorrect_answer2');
                $incorrectAnswer2 = htmlspecialchars($ia2);
                $i2_answer = new Answer();  
                $i2_answer->isCorrect = 0;
                $i2_answer->answerText = $incorrectAnswer2;
                $answers[] = $i2_answer;
                
                $ia3 = filter_input(INPUT_POST, 'incorrect_answer3');
                $incorrectAnswer3 = htmlspecialchars($ia3);
                $i3_answer = new Answer(); 
                $i3_answer->isCorrect = 0;
                $i3_answer->answerText = $incorrectAnswer3;
                $answers[] = $i3_answer;
            }
            else if($questionType === "true_false")
            {
                $answers = array();
                 
                $tf = $_POST['true_false_correct_answer'];     
                $correctAnswer = htmlspecialchars($tf); //will either be True or False
                $answer = new Answer(); 
                $answer->isCorrect = 1; //only going to save one answer for t/f
                $answer->answerText = $correctAnswer;
                 
                $answers[] = $answer;               
            }
                //make question object
                $question = new Question();
                //add the answers to the question object's array
                $question->answers = $answers;
                $question->questionText = $questionText;
                $question->questionType = $questionType;
                $question->correctAnswerPageNumber = $pageNumber; 
                //add the question to the Quiz 
                $quiz->questionList[] = $question;
                //if everything was successful, increment question counter by 1
                $questionCounter++;
                
                if($questionCounter <= $totalNumQuestions)
                {   //increment the counter
                    $_SESSION['questionCounter'] = $questionCounter; 
                    //add new quiz back to the session
                    $_SESSION['quiz'] = serialize($quiz); 
                    //go back to the view and enter in another question
                    include '../view/admin_create_quiz.php';
                    break;
                }
                else
                {
                    $_SESSION['quiz'] = serialize($quiz);
                    //direct user to the confirmation page for the quiz
                    include '../view/admin_save_new_quiz.php';
                    //be sure to set a question limit to 20 questions per quiz! 
                }
        } 
        else //if the quiz is not set yet
        {
            //this doesn't work yet because it doesn't send the list of quizzes back
            $_SESSION['quiz'] = serialize($quiz);
            include '../view/admin_quiz_list.php';
        } 
        break;
    case 'saveQuiz' : //permanently saves the quiz, questions and answers to the DB
        $quiz = unserialize($_SESSION['quiz']);
        //need to make sure a quiz doesn't already exist for that chapter BEFORE saving quiz to avoid duplicates
        //could I figure out ajax for this? 
        
        //save quiz to the database
        $results = SaveQuiz($quiz);
        if($results)
        {   //if the quiz successfully saved, get the new list of quizzes
            $listOfQuizzes = GetCourseQuizzes($quiz->courseID);
            include'../view/admin_quiz_list.php';
        }
        else
        {
            //do some error checking if the quiz didn't save
        }
        break;
    case 'viewQuiz' : 
        //this will just go to the database and display the quiz like the quiz confirmation page
        //make sure this is getting the right quiz ID
        $qID = filter_input(INPUT_POST, 'quizID');
        $quizID = htmlspecialchars($qID);
        
        $cNum = filter_input(INPUT_POST, 'chapterNumber');
        $chapterNumber = htmlspecialchars($cNum);
        
        $nQues = filter_input(INPUT_POST, 'numQuestions');
        $numQuestions = htmlspecialchars($nQues);
        //now take the quizID and get the quiz object from the database
        //make a new quiz object
        $quiz = new Quiz();
        $quiz->quizID = $quizID;
        $quiz->quizChapterNumber = $chapterNumber;
        $quiz->numberOfQuestions = $numQuestions;
        //go get the questions and answers
        $quiz->questionList = GetQuizByQuizID($quiz);
        //put the quiz into the session
        $_SESSION['quiz'] = serialize($quiz);
        //make a display quiz page where they can click to edit or click to delete the quiz? 
        include '../view/admin_view_quiz.php';
        break;
    case 'deleteQuiz' :
        $quiz = unserialize($_SESSION['quiz']);
        
        $qID = $quiz->quizID;
 
        $success = DeleteQuiz($qID);
        if($success)
        {
            include   '../view/admin_quiz_list.php';
        }
        break;
    case 'editQuiz':
        
        break;
    default:
        break;
}
    


