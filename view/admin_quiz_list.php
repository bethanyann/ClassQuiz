<?php
$loggedInStatus = 0;
//make sure admin is signed in
//$username = $_SESSION['username'];
//if($username != 'admin') //this works!!!!!!
//{
//    header( "Location: home.php" ); die; //redirect user to home page if they are not admin
//} 
//get the admin account info
//get admin list of courses

$courseID = $_SESSION['courseID'];
?>
<!DOCTYPE html>
 

<script>

    function validateChapter(chNum,courseID) {
    if (chNum === "" || isNaN(chNum)) {
        document.getElementById("chapterError").innerHTML = "Please enter a valid Chapter Number";
        document.getElementById("quiz_chapter_num").innerHTML = "";
        return;
    } 
    else
    { 
        var ajaxRequest;
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            ajaxRequest = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        }  
        //function that will receive data sent from the server and will update error message				
        ajaxRequest.onload = function(){
           if(ajaxRequest.readyState === 4 && ajaxRequest.status === 200)
           {
                var result = ajaxRequest.responseText;
                if(result === "true")
                {
                    //if result is true that means the chapter exists in the database already and 
                    //it shouldn't be saved
                   document.getElementById("chapterError").innerHTML = "A quiz for Chapter " + chNum + " exists already. Please select a different chapter"; 
                   var button = document.getElementById("new_quiz_button");
                   button.disabled = true;
                }
                else
                {
                   document.getElementById("chapterError").innerHTML = "*";
                   var button = document.getElementById("new_quiz_button");
                   button.disabled = false;
                }
           }
        };
        
        var queryString = "?n=" + chNum + "&c=" + courseID;
        ajaxRequest.open("GET","../ajax_validation.php"+queryString,true);
        ajaxRequest.send();          
    }
    function validateNumQuestions(numQ)
    {
        if(numQ < 0 || numQ > 20)
        {
            document.getElementById("numQuestionError").innerHTML = "Limit of 20 questions per quiz"; 
            var button = document.getElementById("new_quiz_button");
                   button.disabled = true;
        }
        else
        {
            document.getElementById("numQuestionError").innerHTML = "*";
            var button = document.getElementById("new_quiz_button");
                   button.disabled = false;
        }
    }
}
</script>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>View Course Quizzes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/classquiz_main.css">
    <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>

<body height="100">
    <?php include '../shared/navigation.php'; ?>
    <div class="container-fluid" style="padding-left:0px; min-height: 400px"> 
        <?php include '../shared/admin_navigation.php'; ?>
    <!-- IF THERE ARE NO QUIZZES FOR THE SPECFIC COURSE -->
        <?php if (!$listOfQuizzes || $listOfQuizzes===null): ?> 
            <div class="row">
                <div class="col-sm-7">   <!--be sure to set a question limit to 20 questions per quiz-->
                    <h2> <?php echo $_SESSION['courseName'] ?></h2>
                    <br>
                    <h3>This class doesn't have any quizzes set up yet.</h3>
                    <br>
                    <h4> Set up a new quiz:</h4> <br>
                    <form class="form-group" action="quiz_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
                        <table class="table table-responsive ">
                            <tr class="form-group">
                                <td class="col-sm-4 text-left"><label for="numQuestions">Number of questions on the quiz: </label></td>
                                <td class="col-sm-1 text-right"><input class="form-control" type="text"  name="numQuestions" id="num_questions" onkeyup="validateNumQuestions(this.value)" style="width:150px;" required></td>
                                <td><span class="error" id="numQuestionError">*</span></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-sm-4 text-left"><label for="chapterNum">Quiz Chapter Number: </label></td>          
                                <td class="col-sm-1 text-right"><input class="form-control" type="text" name="chapterNum" id="quiz_chapter_num" onkeyup="validateChapter(this.value,<?php echo $courseID ?>)" style="width:150px;" required></td>
                                <td><span class="error" id="chapterError">*</span></td>
                            </tr>
                            <tr class="form-group">
                                <td><input type="hidden" name="action" value="createNewQuiz"</td>
                                <td><button type="submit" class="btn btn-primary" name="action" value="createNewQuiz">Build New Quiz &raquo;</button><td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
    <!-- IF THERE ARE QUIZZES FOR THE SPECFIC COURSE -->
        <?php else: ?>
            <div class="row">
                <div class="col-sm-7">
                    <h2 class="text-center"> <?php echo $_SESSION['courseName'] ?></h2>
                    <h3>Existing Quizzes : </h3>
                    <form action="quiz_controller.php" method="POST">
                        <table class="table table-responsive">    
                            <?php foreach ($listOfQuizzes as $quiz) : ?>                       
                                <tr class="table-hover"> 
                                    <td>
                                        <button type="submit" class="btn btn-primary"  name="action" value="viewQuiz">
                                            <h5>Chapter <?php echo htmlspecialchars($quiz['quizChapterNum']) ?> Quiz</h5> 
                                            <h5>- - - - - -</h5>
                                            <h6><?php echo htmlspecialchars($quiz['quizNumQuestions']) ?> Questions</h6>
                                            <input type="hidden" name="chapterNumber" value="<?php echo $quiz['quizChapterNum']?>">
                                            <input type="hidden" name="numQuestions" value="<?php echo $quiz['quizNumQuestions']?>">
                                            <input type="hidden" name="quizID" value="<?php echo $quiz['quizID'] ?>">
                                        </button>
                                    </td>      
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </form>
                </div> 
            
                <div class="col-sm-7">
                    <h3> Set up a new quiz:</h3> <br>
                    <form class="form-group" action="quiz_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
                        <table class="table table-responsive ">
                            <tr class="form-group">
                                <td class="col-sm-4 text-left"><label for="numQuestions">Number of questions on the quiz: </label></td>
                                <td class="col-sm-1 text-right"><input class="form-control" type="text"  name="numQuestions" style="width:150px;" required></td>
                                <td><span class="error" id="numQuestionError">*</span></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-sm-4 text-left"><label for="chapterNum">Quiz Chapter Number: </label></td>          
                                <td class="col-sm-1 text-right"><input class="form-control" type="text" name="chapterNum" id="quiz_chapter_num" onkeyup="validateChapter(this.value,<?php echo $courseID ?>)" style="width:150px;" required></td>
                                <td><span class="error" id="chapterError" >*</span></td>
                            </tr>
                            <tr class="form-group">
                                <td><input type="hidden" name="action" value="createNewQuiz"</td>
                                <td><button type="submit" class="btn btn-primary" id="new_quiz_button" name="action" value="createNewQuiz">Build New Quiz &raquo;</button><td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        
        
        </div> <!-- END OF CONTAINER -->
    <?php endif; ?> 
</div> <!-- /container -->    
<footer>
    <p class="text-center">&copy; capstone project 2017 - bethany ann</p>
</footer>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.min.js"><\/script>');</script>
<script src="../scripts/vendor/bootstrap.min.js"></script>
<script src="../scripts/classquiz.js"></script>

</body>
</html>
