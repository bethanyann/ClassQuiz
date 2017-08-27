<?php

// ***** MODEL *****
// 
// LOCAL CONNECTION
$dsn = 'mysql:host=localhost;dbname=class_quiz';
$username = 'root';
$password = '';

//GET DATABASE
try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo $error_message;
}

//This function gets the courses associated with the currently-logged-in administrator
function GetAdminCourses($adminID) {
    global $db;

    try {
        $query = 'SELECT course.courseID, course.courseName ' .
                'FROM course ' .
                'JOIN admin_courses ON course.courseID = admin_courses.courseID ' .
                'JOIN admin ON admin_courses.adminID = admin.adminID ' .
                'WHERE admin.adminID = :adminIDPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':adminIDPlaceholder', $adminID);
        $statement->execute();

        $results = $statement->fetchAll();
        $statement->closeCursor();
    } catch (Exception $ex) {
        $results = false;
    }
    return $results;
}

//This function gets the Quizzes associated with a certain Course selection 
function GetCourseQuizzes($courseID) {
    global $db;

    try {
        $query = 'SELECT quizID, quizChapterNum, quizNumQuestions ' .
                 'FROM quiz ' .
                 'WHERE courseID = :courseIDPlaceholder';
        $statement = $db->prepare($query);
        $statement->bindValue(':courseIDPlaceholder', $courseID);
        $statement->execute();

        $results = $statement->fetchAll();
        $statement->closeCursor();
    } catch (Exception $ex) {
        $results = false;
    }
    return $results;
}
//this function gets a selected quiz, questions, and answers by quizID #
function GetQuizByQuizID($quiz)
{
    global $db;
   
    try {
        $query = 'SELECT * FROM question ' .
                 'WHERE quizID = :quizIDPlaceholder';
        
        $statement = $db->prepare($query);
        $statement->bindValue(':quizIDPlaceholder', $quiz->quizID);
        $statement->execute();

        $results = $statement->fetchAll();
        
        foreach($results as $result)
        {
            $question = new Question();
            $question->questionID = $result['questionID'];
            $question->questionType = $result['questionType'];
            $question->questionText = $result['questionText'];
            $question->correctAnswerPageNumber = $result['correctAnswerPageNumber'];
            //add the question to the quiz's array
            $quiz->questionList[] = $question;
        }
        //$questionList = $quiz->questionList;
        //ok now for each question we need to go and get the answers
        foreach($quiz->questionList as $question)
        {
             try {
                    $query = 'SELECT * FROM answer ' .
                    'WHERE questionID = :questionIDPlaceholder';
        
                    $statement = $db->prepare($query);
                    $statement->bindValue(':questionIDPlaceholder', $question->questionID);
                    $statement->execute();

                    $answerResults = $statement->fetchAll();
                    
                    foreach($answerResults as $ar)
                    {
                        $answer = new Answer();
                        
                        $answer->answerID = $ar['answerID'];
                        $answer->answerText = $ar['answerText'];
                        $answer->isCorrect = $ar['isCorrect'];
                        $answer->questionID = $ar['questionID'];
                        //add the question to the quiz's array
                        $question->answers[] = $answer;
                    }
                  }
                  catch(Exception $ex){ }
        } //end FOREACH question loop
         $questionList = $quiz->questionList;
         $statement->closeCursor();
    }//end TRY 
    catch(Exception $ex)
    {
       $results = false; 
    }
    
    return $questionList; //might change this cuz technically I want to return the full quiz object
}

function SaveQuiz($quiz) {
    global $db;
    $results = false;

    try {
        $query = 'INSERT INTO quiz
               (courseID, quizChapterNum, quizNumQuestions)
               VALUES
               (:courseIDPlaceholder, :chapterNumberPlaceholder, :numQuestionsPlaceholder)';
        $statement = $db->prepare($query);
        $statement->bindValue(':courseIDPlaceholder', $quiz->courseID);
        $statement->bindValue(':chapterNumberPlaceholder', $quiz->quizChapterNumber);
        $statement->bindValue(':numQuestionsPlaceholder', $quiz->numberOfQuestions);
        $statement->execute();
        //get the quizID that was just inserted
        $id = $db->lastInsertId();

        if (isset($id)) {
            //save the questions & answers
            $results = SaveQuestions($id, $quiz);
        }
        $statement->closeCursor();
    } catch (Exception $ex) {
        
    }
    return $results;
}

function SaveQuestions($id, $quiz) {
    global $db;
    $results = false;
    
    //starting the loop with the quiz's list of questions array
    $questionList = $quiz->questionList;
    foreach ($questionList as $question) {
        //insert question first
        try {
            $query = 'INSERT INTO question (quizID, questionType, questionText, correctAnswerPageNumber)
                        VALUES
                        (:quizIDPlaceholder, :questionTypePlaceholder, :questionTextPlaceholder, :correctAnswerPlaceholder)';
            $statement = $db->prepare($query);
            $statement->bindValue(':quizIDPlaceholder', $id); 
            $statement->bindValue(':questionTypePlaceholder', $question->questionType);
            $statement->bindValue(':questionTextPlaceholder', $question->questionText);
            $statement->bindValue(':correctAnswerPlaceholder', $question->correctAnswerPageNumber);
            $statement->execute();

            $question_id = $db->lastInsertId();

            foreach ($question->answers as $answer) {   //now insert the answers attached to each question
                global $db; //do i need this again? rprobably not bc its the same connection
                try {
                    $query = 'INSERT INTO answer( questionID, answerText, isCorrect )
                        VALUES
                        ( :questionIDPlaceholder, :answerTextPlaceholder, :isCorrectPlaceholder)';
                    $statement = $db->prepare($query);
                    $statement->bindValue(':questionIDPlaceholder', $question_id);
                    $statement->bindValue(':answerTextPlaceholder', $answer->answerText);
                    $statement->bindValue(':isCorrectPlaceholder', $answer->isCorrect);
                    $success = $statement->execute();
                    $row_count = $statement->rowCount();
                    
                    if($success)
                    {    
                        $results = true;
                    }
                } catch (Exception $ex) {
                    $results = false;
                }
            } //end of the FOREACH block for the answers
            $results = true;
        } //end of the FIRST TRY BLOCK
        catch (Exception $ex) {
            print $ex;
            $results = false;
        }  
    } //end of the FOREACH block for the questions
    return $results;
}
