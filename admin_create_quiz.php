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

$quizCount = $numQuestions;

?>
<!DOCTYPE html>
<!-- THIS DISPLAYS THE QUIZZES ATTACHED TO A SPECFIC QUIZ  --> 
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>View Course Quizzes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/classquiz_main.css">
    <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head><
<body>
    <?php include 'shared/navigation.php'; ?>
    <div class="container-fluid" style="min-height: 400px">
        <!-- Example row of columns -->
        <div class="row">
            <?php include 'shared/admin_navigation.php'; ?>
            <div class="col-sm-7">
                <h4> Create a new quiz for <?php echo $_SESSION['courseName'] ?></h4>
                <div class="row" style="margin-left:20px;">
                    <h5>Select the type of question to add: </h5>
                       <input type="radio" id="toggle" name="questionType" value="multiple_choice" style="margin-right:6px;"/><label style="padding-right:20px;">Multiple Choice</label>
                       <input type="radio" id="toggle" name="questionType" value="true_false" style="margin-right:6px;"/><label style="padding-right:20px;">True False</label>       
                       <input type="radio" id="toggle" name="questionType" value="fill_blank" style="margin-right:6px;"/><label style="padding-right:20px;">Fill in the Blank</label>
                </div>
                <div class="row" id="multiple" style="margin-left:20px; display:none;">
                         
                         <form class="form-group" action="admin_controller.php" method="POST">
                             <table class="table table-responsive">
                                 <tr>
                                    <td class="col-sm-4"><label>Question:</label></td>
                                    <td><input type="text" name="question" class="form-control"></td>
                                 </tr>
                                 <tr>
                                    <td class="col-sm-4"><label>correct answer choice:</label></td>
                                    <td><input type="text" name="correct_answer" class="form-control"></td>
                                 </tr>
                                 <tr>
                                    <td class="col-sm-4"><label>incorrect answer choice 2:</label></td>
                                    <td><input type="text" name="incorrect_answer" class="form-control"></td>
                                 </tr>
                                 <tr>
                                    <td class="col-sm-4"><label>incorrect answer choice 3:</label></td>
                                    <td><input type="text" name="incorrect_answer2" class="form-control"></td>
                                 </tr>
                                 <tr>
                                    <td class="col-sm-4"><label>incorrect answer choice 4:</label></td>
                                    <td><input type="text" name="incorrect_answer3" class="form-control"></td>
                                 </tr>
                                 <tr>
                                    <td class="col-sm-4"></td>
                                    <td><button type="submit" class="btn btn-primary" name="action" value="saveQuestion">Save Question &raquo;</button><td>
                                 </tr>
                             </table>   
                         </form>
                </div>
                <div class="row" id="truefalse" style="margin-left:20px; display:none;">
                         true false question here
                </div>
                <div class="row" id="fillblank" style="margin-left:20px; display:none;">
                         fill in the blank question here
                </div>
            </div>
        </div>
        <footer>
            <p class="text-center">&copy; capstone project 2017</p>
        </footer>
    </div> <!--end of container -->

        
</body>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.min.js"><\/script>');</script>
    <script src="../scripts/vendor/bootstrap.min.js"></script>
   

</html>
<script type="text/javascript">
    $(document).ready(function(){    
       
       $('input[type=radio]').change(function(){
            //alert(this.value);
            
            if(this.value === 'multiple_choice')
            {
                $('#multiple').css('display','block');
                $('#truefalse').css('display', 'none');
                $('#fillblank').css('display', 'none');
            }
            else if(this.value === 'true_false')
            {
                $('#multiple').css('display','none');
                $('#truefalse').css('display', 'block');
                $('#fillblank').css('display', 'none');
            }
            else if(this.value === 'fill_blank')
            {
                $('#multiple').css('display', 'none');
                $('#truefalse').css('display', 'none');
                $('#fillblank').css('display', 'block');
            }
        });
    });

</script>