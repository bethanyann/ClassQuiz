<?php

// ***** MODEL *****
// 
// LOCAL CONNECTION
$dsn = 'mysql:host=localhost;dbname=class_quiz';
$user_name = 'root';
$password_ = '';

//GET DATABASE
try {
    $db = new PDO($dsn, $user_name, $password_);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo $error_message;
}

//CHECKS TO SEE IF USER IS AN ADMIN
function checkUserType($username) {
    global $db;

    try {

        $query = 'SELECT COUNT(*) FROM admin WHERE adminUsername = :adminUsernamePlaceholder';
        $statement = $db->prepare($query);
        $statement->bindValue('adminUsernamePlaceholder', $username);
        $statement->execute();

        $numRows = $statement->fetchColumn();

        if ($numRows == 0) {
            $isAdmin = FALSE;
        } 
        else {
            $isAdmin = TRUE;
        }
        return $isAdmin;
    } catch (Exception $ex) {
        echo $ex;
    }
}
//GETS USER INFO FROM DB USING THE USERNAME
function getUserInfo($username, $password)
{
    global $db;
    try {
        $query = 'SELECT * FROM admin WHERE adminUsername = :adminUsernamePlaceholder';
        $statement = $db->prepare($query);
        $statement->bindValue('adminUsernamePlaceholder', $username);
        $statement->execute();
        
        $results = $statement->fetchAll();
        $userPassword = $results[0]['adminPassword'];
        
        $correct = password_verify($password,$userPassword );

        $statement->closeCursor();
        if($correct)
        {
            return $results;
        }
        else { return false;}
    } catch (Exception $ex) {
        echo $ex;
    }
}
//temp function to add hashed password to admin account - delete eventually
function admin_insert($username, $hash) {
    global $db;
    try {
        $query = 'UPDATE admin '
                . 'SET adminPassword = :passwordPlaceholder '
                . 'WHERE adminUsername = :adminUsernamePlaceholder';
        $statement = $db->prepare($query);
        $statement->bindValue(':adminUsernamePlaceholder', $username);
        $statement->bindValue(':passwordPlaceholder', $hash);
        $statement->execute();

        if ($statement) {
            echo 'yay!';
        }
        $statement->closeCursor();
    } catch (Exception $ex) {
        
    }
}
