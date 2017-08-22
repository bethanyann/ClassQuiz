<?php

/** this will link a quiz to a specfic course
 *  and will have a set number of questions
 *
 * 
 */
class Quiz {
 
    public $quizID;
    public $quizChapterNumber;
    public $courseID;
    public $numberOfQuestions;
    
    //this will hold all of the qustions in a particular quiz
    public $questionList = array();
    
    function __construct() { }
}
