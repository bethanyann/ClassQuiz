<?php

class Question {
    //put your code here
    //public $quizID;
    
    public $questionID;
    public $questionType;
    public $questionText;
    public $correctAnswerPageNumber;
    
    public $answers = array();
    
    function __construct() {}
    
}
