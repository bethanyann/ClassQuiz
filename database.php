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

//This function gets the courses associated with the currently-logged-in adminsitrator
function GetAdminCourses($adminID) {
    global $db;

    try {
        $query ='SELECT course.courseID, course.courseName ' .
                'FROM course ' .
                'JOIN admin_courses ON course.courseID = admin_courses.courseID ' .
                'JOIN admin ON admin_courses.adminID = admin.adminID ' .
                'WHERE admin.adminID = :adminIDPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':adminIDPlaceholder', $adminID);
        $statement->execute();
        
        $results = $statement->fetchAll();
        $statement->closeCursor();
    } 
    catch (Exception $ex) {       
        $results = false;
    }
    return $results;
}

//This function gets the Quizzes associated with a certain Course selection 
function GetCourseQuizzes($courseID) {
    global $db;
    
    try {
        $query ='SELECT quizID, quizChapterNum, quizNumQuestions ' .
                'FROM quiz ' .
                'WHERE courseID = :courseIDPlaceholder';

        $statement = $db->prepare($query);
        $statement->bindValue(':courseIDPlaceholder', $courseID);
        $statement->execute();
        
        $results = $statement->fetchAll();
        $statement->closeCursor();
    }
    catch (Exception $ex) {       
        $results = false;
    }
    return $results;
}
