<?php
// ***** MODEL *****
// 
// LOCAL CONNECTION
$dsn = 'mysql:host=localhost;dbname=class_quiz';
$username = 'root';
$password = '';

//GET DATABASE CONNECTION
try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo $error_message;
}
//GETS THE COURSES ASSOCIATED WITH THE ADMIN ACCOUNT THAT IS LOGGED IN
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
        echo $ex;
        $results = false;
    }
    return $results;
}
//GETS QUIZZES ASSOCIATED WITH A CERTAIN COURSE SELECTION
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
        echo $ex;
    }
    return $results;
}
//GETS ALL STUDENTS NOT ASSIGNED TO A SPECFIC COURSE YET
function GetAllStudentsNotInACertainCourse($courseID)
{
    global $db;   
    try
    {
        $query = 'SELECT * FROM student WHERE studentID NOT IN'
               . '(SELECT studentID FROM student_course WHERE courseID = :courseIDPlaceholder)';
        
        $statement = $db->prepare($query);
        $statement->bindValue(':courseIDPlaceholder', $courseID);
        $statement->execute();

        $results = $statement->fetchAll();
    } catch (Exception $ex) {
        echo $ex;
        $results = false;
    }
    return $results;
}
//GETS STUDENTS THAT ARE ASSIGNED TO A SPECFIC COURSE
function GetCourseStudents($courseID)
{
    global $db;   
    try
    {
        $query = 'SELECT * FROM student WHERE studentID IN'
               . '(SELECT studentID FROM student_course WHERE courseID = :courseIDPlaceholder)';
        
        $statement = $db->prepare($query);
        $statement->bindValue(':courseIDPlaceholder', $courseID);
        $statement->execute();

        $results = $statement->fetchAll();
    } catch (Exception $ex) {
        echo $ex;
        $results = false;
    }
    return $results;
}
//INSERT NEW STUDENT INTO THE DB
function AddNewStudent($studentID,$firstName,$lastName)
{
     global $db;
    try {
        $query = 'INSERT INTO student(studentID,studentFirstName,studentLastName)'
                . 'VALUES(:studentIDPlaceholder,:firstNamePlaceholder, :lastNamePlaceholder)';
        
        $statement=$db->prepare($query);
        $statement->bindValue(':studentIDPlaceholder',$studentID);
        $statement->bindValue(':firstNamePlaceholder',$firstName);
        $statement->bindValue(':lastNamePlaceholder',$lastName);
        $rows = $statement->execute();
        if($rows)
        {
            $success=true;
        }
        
    }
    catch(Exception $ex)
    {
        $success = false;
        echo $ex;
    }
    return $success;
}
//DELETES A STUDENT FROM A COURSE
 function RemoveStudentFromCourse($studentID,$courseID)
 {
       $results = false;
       global $db;
    try {
        $query = 'DELETE FROM student_course
                    WHERE studentID = :studentIDPlaceholder AND courseID = :courseIDPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':studentIDPlaceholder', $studentID);
        $statement->bindValue(':courseIDPlaceholder', $courseID);
        $success = $statement->execute();
        $row_count = $statement->rowCount();

        if ($success && $row_count > 0) {
            $results = true;
        }
    } catch (Exception $ex) {
        echo $ex;
    }
    return $results;
 }
 //EDIT STUDENT INFO FOR EXISTING STUDENT
 function EditStudent($studentID,$studentFirstName,$studentLastName)
 {
     global $db;
     $results = false;
     
     try {
         $query = 'UPDATE student '.
                 'SET studentFirstName = :studentFirstNamePlaceholder,
                     studentLastName = :studentLastNamePlaceholder ' 
                 . 'WHERE studentID = :studentIDPlaceholder';
         $statement = $db->prepare($query);
         $statement->bindValue(':studentFirstNamePlaceholder', $studentFirstName);
         $statement->bindValue(':studentLastNamePlaceholder', $studentLastName);
         $statement->bindValue(':studentIDPlaceholder', $studentID);
         
         $success = $statement->execute();
         
     } catch (Exception $ex) {
            echo $ex;        
     }
     return $success;
 }
 
//ASSIGNS A STUDENT TO A SPECFIC COURSE
function AssignStudentToCourse($studentID,$courseID)
{
    global $db;
    try {
        $query = 'INSERT INTO student_course (studentID,courseID)'
                . 'VALUES(:studentIDPlaceholder,:courseIDPlaceholder)';
        
        $statement = $db->prepare($query);
        $statement->bindValue(':studentIDPlaceholder', $studentID);
        $statement->bindValue(':courseIDPlaceholder', $courseID);
        $rows = $statement->execute();
        
        if($rows)
        {
            $success=true;
        }
        
    } catch (Exception $ex) {
        echo $ex;
        $success = false;
    }
    return $success;
}
//GETS A SELECTED QUIZ, QUESTIONS, AND ANSWERS BY QUIZ ID#
function GetQuizByQuizID($quiz) {
    global $db;

    try {
        $query = 'SELECT * FROM question ' .
                'WHERE quizID = :quizIDPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':quizIDPlaceholder', $quiz->quizID);
        $statement->execute();

        $results = $statement->fetchAll();
        //get each question associated with a quiz
        foreach ($results as $result) {
            $question = new Question();
            $question->questionID = $result['questionID'];
            $question->questionType = $result['questionType'];
            $question->questionText = $result['questionText'];
            $question->correctAnswerPageNumber = $result['correctAnswerPageNumber'];
            //add the question to the quiz's array
            $quiz->questionList[] = $question;
        }
        //for each question, get the associated answers
        foreach ($quiz->questionList as $question) {
            try {
                $query = 'SELECT * FROM answer ' .
                        'WHERE questionID = :questionIDPlaceholder';

                $statement = $db->prepare($query);
                $statement->bindValue(':questionIDPlaceholder', $question->questionID);
                $statement->execute();

                $answerResults = $statement->fetchAll();

                foreach ($answerResults as $ar) {
                    $answer = new Answer();

                    $answer->answerID = $ar['answerID'];
                    $answer->answerText = $ar['answerText'];
                    $answer->isCorrect = $ar['isCorrect'];
                    $answer->questionID = $ar['questionID'];
                    //add the question to the quiz's array
                    $question->answers[] = $answer;
                }
            } catch (Exception $ex) {
                
            }
        } //end FOREACH question loop
        $questionList = $quiz->questionList;
        $statement->closeCursor();
    }//end TRY 
    catch (Exception $ex) {
        echo $ex;
        $results = false;
    }

    return $questionList; //might change this cuz technically I want to return the full quiz object
}
//SAVES A QUIZ OBJECT TO THE DATABASE
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
        echo $ex;
    }
    return $results;
}
//SAVES ALL THE QUESIONS/ANSWERS ASSOCIATED WITH ONE QUIZ INTO THE DATABASE
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

                    if ($success && $row_count > 0) {
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
//DELETES A QUIZ AND ALL ASSOCIATED QUESTIONS/ANSWERS FROM THE DATABASE
function DeleteQuiz($quizID) {
    global $db;
    $results = false;

    try {
        $query = 'DELETE FROM quiz
                    WHERE quizID = :quizIDPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':quizIDPlaceholder', $quizID);
        $success = $statement->execute();
        $row_count = $statement->rowCount();

        if ($success && $row_count > 0) {
            $results = true;
        }
    } catch (Exception $ex) {
        echo $ex;
    }
    return $results;
}
//MAKES SURE THE CHAPTER NUMBER DOES NOT EXIST ALREADY
function ValidateQuizChapter($cID, $chNum) {
    global $db;

    try {      
        $query = 'SELECT * FROM quiz WHERE quizChapterNum = :chNum AND courseID = :courseID';

        $statement = $db->prepare($query);
        $statement->bindValue(':chNum', $chNum);
        $statement->bindValue(':courseID', $cID);
        $statement->execute();

        if (($row = $statement->fetch(PDO::FETCH_ASSOC)) > 0) {
            $chapterExists = true;     
        } else {
            // no row returned
            $chapterExists = false;
        }
        $statement->closeCursor();
        return $chapterExists;
    } catch (Exception $ex) {
        echo $ex."That didn't work."; //echo the result back to the page
    }
}
//ADDS A NEW COURSE TO THE DATABASE
function AddNewCourse($courseName,$courseID,$adminID)
{
    global $db;
    try{ 
        $query = 'INSERT INTO course (courseID, courseName)
                  VALUES (:courseID, :courseName)';
            $statement = $db->prepare($query);
            $statement->bindValue(':courseID', $courseID);
            $statement->bindValue(':courseName', $courseName);
            $success = $statement->execute();
            
            if($success)
            {
                $query = 'INSERT INTO admin_courses(courseID, adminID)
                  VALUES (:courseID, :adminID)';
                $statement = $db->prepare($query);
                $statement->bindValue(':courseID', $courseID);
                $statement->bindValue(':adminID', $adminID);
                $success = $statement->execute();
            }
            else
            {
                $success = false;
            }
            
        return $success;
    } catch (Exception $ex) {
        echo $ex;
    }
}
//DELETES A COURSE AND IT'S ASSOCIATION TO AN ADMIN ACCOUNT
function DeleteCourse($courseID,$adminID)
{
    global$db;
    
    try{
        $query = 'DELETE FROM course WHERE courseID = :courseIDPlaceholder';
        $statement = $db->prepare($query);
        $statement->bindValue(':courseIDPlaceholder', $courseID);   
        $success = $statement->execute();
        
        if($success)
        {
            try{
                $query = 'DELETE FROM admin_courses WHERE courseID = :courseIDPlaceholder AND adminID = :adminIDPlaceholder';
                $statement = $db->prepare($query);
                $statement->bindValue(':courseIDPlaceholder', $courseID); 
                $statement->bindValue(':adminIDPlaceholder', $adminID); 
                $success = $statement->execute();

            } catch (Exception $ex) {
                echo $ex;
                $success=false;
            }         
        }
    } catch (Exception $ex) {
        echo $ex;
        $success = false;
    }
    return $success;
}
