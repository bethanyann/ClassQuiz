<?php

class Question {
    //put your code here
    //public $quizID;
    
    public $questionID;
    public $questionType;
    public $questionText;
    public $correctAnswerPageNumber;
    
    public $answers = array();
    
    function __construct($the_answers, $the_questionText, $the_type, $the_pageNumber) 
    { 
        $this->answers = $the_answers;
        $this->questionText = $the_questionText;
        $this->questionType = $the_type;
        $this->correctAnswerPageNumber = $the_pageNumber;
    }
}
