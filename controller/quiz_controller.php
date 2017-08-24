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
        
        $_SESSION['quiz'] = $quiz;
        //store the new quiz in a global variable to keep it until the quiz is ready to be saved
        //--working? 
      
        //store the question numbers in the session        
        $_SESSION['numQuestions'] = $numQuestions;
        $_SESSION['questionCounter'] = 1;  //set the counter to 1
      
        include '../view/admin_create_quiz.php';
        break;
    
    case 'saveQuestion': //this action will be hit every time the user wants to save a question/answer set
        $quiz = $_SESSION['quiz'];
        
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
                //make a new array and a new answer object 
                $answers = array();
                $answer = new Answer(); //might need to make a new answer object
                
                //there will be 1 correct answer
                $ca = filter_input(INPUT_POST, 'correct_answer');
                $correctAnswer = htmlspecialchars($ca);
        
                $answer->isCorrect = 1;
                $answer->answerText = $correctAnswer;
                $answers[] = $answer;
             
                //and 3 incorrect answers
                $ia1 = filter_input(INPUT_POST, 'incorrect_answer1');
                $incorrectAnswer1 = htmlspecialchars($ia1);
                $answer->answerText = $incorrectAnswer1;
                $answer->isCorrect = 0;
                $answers[] = $answer;
                
                $ia2 = filter_input(INPUT_POST, 'incorrect_answer2');
                $incorrectAnswer2 = htmlspecialchars($ia2);
                $answer->answerText = $incorrectAnswer2;
                $answer->isCorrect = 0;
                $answers[] = $answer;
                
                $ia3 = filter_input(INPUT_POST, 'incorrect_answer3');
                $incorrectAnswer3 = htmlspecialchars($ia3);
                $answer->answerText = $incorrectAnswer3;
                $answer->isCorrect = 0;
                $answers[] = $answer;
                
                //make question object
                $question = new Question($answers, $questionText, $questionType, $pageNumber);//add the answers to the question array
            
                //add the question to the Quiz 
                $quiz->questionList[] = $question;
                
                if($questionCounter <= $totalNumQuestions)
                {
                    $_SESSION['questionCounter'] = $questionCounter + 1; //increment the counter
                    //go back to the view and enter in another question
                    include '../view/admin_create_quiz.php';
                    break;
                }
                else
                {
                    //display a page for the user to see all of the questions and answers 
                    //so just iterate through the quiz object with a double loop
                    //for each question, display all answers in answer array
                    //then include a button at the bottom of the screen to save the quiz
                    //include another button that will allow the user to edit the quiz
                    //implement edit functionality later on. 
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
            
            
            //TO DO
            //add question to the quiz array HERE
            
            
            
            $_SESSION['questionCounter'] = ($questionCounter - 1);
        }
        else
        {
            //this doesn't work because it doesn't sent the list of quizzes back...
            include '../view/admin_quiz_list.php';
        }
        
        
       
        
        //then save the question to the quiz question array
        //$quiz->$questionArray[x] = $
      
        
        $q = filter_input(INPUT_POST, 'question');
        $ca = filter_input(INPUT_POST, 'correct_answer');   
        $ia1 = filter_input(INPUT_POST, 'incorrect_answer1');
        $ia2 = filter_input(INPUT_POST, 'incorrect_answer2');
        $ia3 = filter_input(INPUT_POST, 'incorrect_answer3');
        
        //dont forget to make a thing for what page # the correct answer can be found on. 
        
        
        //need a quiz object in the database before saving a question
        
        //after everything is saved, 
        //$quizQuestionCount--;
        //if($quizQuestionCount <= $numQuestions {
        //  //go back to admin_create_quiz page 
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
    


