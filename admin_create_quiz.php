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
                       <input type="radio" id="toggle" name="questionType" value="multiple_choice" style="padding-right:10px;"/><label style="padding-right:20px;">Multiple Choice</label>
                       <input type="radio" id="toggle" name="questionType" value="true_false" style="padding-right:10px;"/><label style="padding-right:20px;">True False</label>       
                       <input type="radio" id="toggle" name="questionType" value="fill_blank" style="padding-right:10px;"/><label style="padding-right:20px;">Fill in the Blank</label>
                </div>
                <div class="row" style="margin-left:20px;">
                         multiple choice question here
                </div>
                <div class="row hidden" style="margin-left:20px;">
                         true false question here
                </div>
                <div class="row hidden" style="margin-left:20px;">
                         fill in the blank question here
                </div>
            </div>
        </div>
        <footer>
            <p>&copy; capstone project 2017</p>
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
                
            }
            else if(this.value === 'true_false')
            {
                
            }
            else if(this.value === 'fill_blank')
        });
    });

</script>