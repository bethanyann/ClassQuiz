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

//global quiz variable once a quiz is created;


switch ($action){
    case 'createNewQuiz': // this action is coming in from the admin_quiz_list page 
                          //after an admin says they wanna create a new quiz
        //get the course information out of the session -working
        $courseName = $_SESSION['courseName'];
        $courseID = $_SESSION['courseID'];
        
        //get the quiz information from the form - working
        $chapterNumber = filter_input(INPUT_POST, 'chapterNum');
        $numberQuestions = filter_input(INPUT_POST, 'numQuestions');
        $chapterNum = htmlspecialchars($chapterNumber);
        $numQuestions = htmlspecialchars($numberQuestions);
        
        //make a new quiz object
        $quiz = new Quiz();  //figure out a better way to do a global quiz 
        $quiz->numberOfQuestions = $numQuestions;
        $quiz->quizChapterNumber = $chapterNum;
        $quiz->courseID = $courseID;
        
        $_SESSION['quiz'] = serialize($quiz);
        //store the new quiz in a global variable to keep it until the quiz is ready to be saved
        //--working? 
      
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
            if($questionType === "multiple_choice") //working!
            {   
                //make a new array 
                $answers = array();
                
                //there will be 1 correct answer
                $ca = filter_input(INPUT_POST, 'correct_answer');
                $correctAnswer = htmlspecialchars($ca);
                $answer = new Answer(); 
                $answer->isCorrect = 1;
                $answer->answerText = $correctAnswer;
                $answers[] = $answer;
             
                //and 3 incorrect answers
                $ia1 = filter_input(INPUT_POST, 'incorrect_answer1');
                $incorrectAnswer1 = htmlspecialchars($ia1);
                $answer = new Answer();
                $answer->isCorrect = 0;
                $answer->answerText = $incorrectAnswer1;
                $answers[] = $answer;
                
                $ia2 = filter_input(INPUT_POST, 'incorrect_answer2');
                $incorrectAnswer2 = htmlspecialchars($ia2);
                $answer = new Answer();  
                $answer->isCorrect = 0;
                $answer->answerText = $incorrectAnswer2;
                $answers[] = $answer;
                
                $ia3 = filter_input(INPUT_POST, 'incorrect_answer3');
                $incorrectAnswer3 = htmlspecialchars($ia3);
                $answer = new Answer(); 
                $answer->isCorrect = 0;
                $answer->answerText = $incorrectAnswer3;
                $answers[] = $answer;
                
                //make question object
                $question = new Question();
                $question->answers = $answers;//add the answers to the question object's array
                $question->questionText = $questionText;
                $question->questionType = $questionType;
                $question->correctAnswerPageNumber = $pageNumber; 
                //add the question to the Quiz 
                $quiz->questionList[] = $question;
                
                //if everything was successful, increment question counter by 1
                $questionCounter++;
                
                if($questionCounter <= $totalNumQuestions)
                {
                    $_SESSION['questionCounter'] = $questionCounter; //increment the counter
                    $_SESSION['quiz'] = serialize($quiz); //add new quiz back to the session
                    //go back to the view and enter in another question
                    include '../view/admin_create_quiz.php';
                    break;
                }
                else
                {
                    $_SESSION['quiz'] = serialize($quiz);
                    include '../view/admin_save_new_quiz.php';
                    //be sure to set a question limit to 20 questions per quiz! 
                }
            }
            else if($question->questionType === "true_false")
            {
                //TO DO
                
                //there will be one correct answer, either "true" or "false"
                
                //isCorrect = true or false 
                //answerText = true or false
            }
            else if($question->questionType === "fill_blank")
            {
                //TO DO
                
                //I don't know if I want to do this one yet or not
                //baby steps
            }
            else
            {
                //print out the error to help debug
                console.print_r("The question type actually is" . " " . $question->questionType);
            }          
        }
        else
        {
            //this doesn't work because it doesn't sent the list of quizzes back...
            $_SESSION['quiz'] = serialize($quiz);
            include '../view/admin_quiz_list.php';
        } 
        break;
    case 'saveQuiz' : //permanently saves the quiz, questions and answers
        $quiz = unserialize($_SESSION['quiz']);
        //need to make sure a quiz doesn't already exist for that chapter BEFORE saving quiz to avoid duplicates
        //could I figure out ajax for this???? 
        $results = SaveQuiz($quiz);
        if($results)
        {   //if the quiz successfully saved
            //get the new list of quizzes 
            $listOfQuizzes = GetCourseQuizzes($quiz->courseID);
            include'../view/admin_quiz_list.php'; //kinda want to rename this page
        }
        else
        {
            //do some error checking if the quiz didn't save
        }
        break;
    case 'viewQuiz' : 
        //this will just go to the database and display the quiz like the quiz conformation page
         //make sure this is getting the right courseID #
        $qID = filter_input(INPUT_POST, 'quizID');
        $quizID = htmlspecialchars($qID);
        
        $cNum = filter_input(INPUT_POST, 'chapterNumber');
        $chapterNumber = htmlspecialchars($cNum);
        
        $nQues = filter_input(INPUT_POST, 'numQuestions');
        $numQuestions = htmlspecialchars($nQues);
        //now take the quizID and get the quiz object from the database
        //this might be hard
        
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
        
        $success = DeleteQuiz($quiz);
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
    


