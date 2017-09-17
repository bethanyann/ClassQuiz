<?php
/* 
 * This is the file that the ajax calls will be directed to, to validate user
 * input. see this website for hints on how to do this https://www.w3schools.com/php/php_ajax_database.asp
 */
//get the class id number
$cID = htmlspecialchars(filter_var($_GET['c'])); 
//get the chapter number
$chNum = htmlspecialchars(filter_var($_GET['n']));
//make the database connection
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
//see if the chapter number exists for that class in the database already
try
{
    global $db;
    $query = 'SELECT * FROM quiz WHERE quizChapterNum = :chNum AND courseID = :courseID';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':chNum',$chNum);
    $statement->bindValue(':courseID',$cID);
    $statement->execute();

        if(($row = $statement->fetch(PDO::FETCH_ASSOC)) > 0) {  
        $chapterExists = true;
        echo "true";//echo the result back to the page
    } 
    else {
        // no row returned
        $chapterExists = false;   
        echo "false";//echo the result back to the page
    }
        $statement->closeCursor();
        return $chapterExists;
    
} 
catch (Exception $ex) {
        echo "That didn't work.";//echo the result back to the page
}
 