<?php
$userType = $_SESSION['userType'];
if ($userType != 'admin') { //this works!!!!!!
    header("Location: ../index.php");
    die; //redirect user to home page if they are not admin
}

?>
<!DOCTYPE html>
<!--This will allow an admin to add students to a class-->
<!--AJAX SCRIPT for the validation -->
<script>
    //ajax function to make sure the chapter does not exist for the course already
    function validateChapter(chNum, courseID) {
        if (chNum === "" || isNaN(chNum) || chNum <= 0) {
            document.getElementById("chapterError").innerHTML = "Please enter a valid Chapter Number";
            document.getElementById("quiz_chapter_num").innerHTML = "";
            return;
        } else
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
            ajaxRequest.onload = function () {
                if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200)
                {
                    var result = ajaxRequest.responseText;
                    if (result === "true")
                    {
                        //if result is true that means the chapter exists in the database already and 
                        //it shouldn't be saved
                        document.getElementById("chapterError").innerHTML = "A quiz for Chapter " + chNum + " exists already. Please select a different chapter";
                        var button = document.getElementById("new_quiz_button");
                        button.disabled = true;
                    } else
                    {   //passed chapter validation, now check the num questions
                        document.getElementById("chapterError").innerHTML = "*";
                        var button = document.getElementById("new_quiz_button");
                        var questionNum = document.getElementById("num_questions").value;
                     
                        if(questionNum !== "" ) //if the question Num is blank don't check it
                        {
                            if(questionNum > 20 || questionNum <= 0 || isNaN(questionNum))
                            { validateNumQuestions(questionNum); }
                            else
                            {   button.disabled = false;    }
                           
                        } else
                        {
                            button.disabled = false;
                        }
                    }
                } else { //put something here if the ajax response doesn't work
                }
            };

            var queryString = "?n=" + chNum + "&c=" + courseID;
            ajaxRequest.open("GET", "../ajax_validation.php" + queryString, true);
            ajaxRequest.send();
        }
    }
    ;
    //validation for the number of quiz questions (is a number, is not zero, is not greater than 20)
    function validateNumQuestions(numQ)
    {
        if (numQ === "" || isNaN(numQ))
        {
            document.getElementById("numQuestionError").innerHTML = "Please enter a valid number";
            var button = document.getElementById("new_quiz_button");
            button.disabled = true;
        } else if (numQ < 0 || numQ > 20)
        {
            document.getElementById("numQuestionError").innerHTML = "Limit of 20 questions per quiz";
            var button = document.getElementById("new_quiz_button");
            button.disabled = true;
        } else
        {
            document.getElementById("numQuestionError").innerHTML = "*";
            var button = document.getElementById("new_quiz_button");

            var chNum = document.getElementById("quiz_chapter_num").value;
            var cID = document.getElementById("courseID").value;

            if (chNum === "") //if the chapter number is not blank then double check that the ch is valid
            {
                button.disabled = false;
            } else
            {
                validateChapter(chNum, cID);
            }
        }


    };

</script>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Quiz View</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">   
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../css/classquiz_main.css">
        <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <?php include '../shared/navigation.php'; ?>
        <div class="container-fluid" style="min-height: 400px">  
        <div class="row">
            <?php include '../shared/admin_navigation.php'; ?>
                <!--IF THERE ARE NO STUDENTS ASSIGNED TO THIS COURSE YET -->
                <?php if (!$listOfStudents || $listOfStudents === null || count($listOfStudents) < 1): ?> 
                    <div class="col-sm-8">   <!--be sure to set a question limit to 20 questions per quiz-->
                        <h2> <?php echo $_SESSION['courseName'] ?></h2>
                        <br>
                            <h3>This class doesn't have any students assigned to it yet.</h3>
                        <br>
                        <br>
                    </div> 
                <!--ELSE IF THERE ARE STUDENTS ASSIGNED TO THIS COURSE -->
                <?php else: ?>
                        <div class="col-sm-7">    <!--DISPLAY STUDENTS ASSIGNED TO THIS COURSE -->                                      
                            <h2 class="text-center"> <?php echo $_SESSION['courseName'] ?></h2>
                            <h3>Students assigned to this course: </h3>
                                <form action="admin_controller.php" method="POST">
                                    <table class="table table-responsive"> 
                                        <thead>
                                           <th>Student ID#</th> 
                                           <th>First Name</th>
                                           <th>Last Name</th>
                                           <th>remove student</th>
                                           <th>edit student</th>
                                        </thead>
                                        <tbody>
                                        <tr class="table-hover">
                                            <?php foreach ($listOfStudents as $student) : ?>                                                     
                                          <!--  <td class="col-sm-1" style="display:inline;">-->
                                            <td><?php echo htmlspecialchars($student['studentID']) ?></td>    
                                            <td><?php echo htmlspecialchars($student['studentFirstName']) ?></td>
                                            <td><?php echo htmlspecialchars($student['studentLastName']) ?></td>
                                            <td><button type="submit" class="btn btn-primary" name="action" value="removeStudent">remove from class</button></td>
                                            <td><button type="submit" class="btn btn-primary"  name="action" value="viewQuiz">edit student</button></td>
                                                <input type="hidden" name="studentID" value="<?php echo $student['studentID'] ?>">
                                                <input type="hidden" name="courseID" value="<?php echo $_SESSION['courseID'] ?>">
                                            <?php endforeach; ?>
                                        </tbody>           
                                    </table>
                                </form>
                        </div>
                    </div>
            <!--END OF IF STATEMENT -->  
                <?php endif; ?> 
                <div class="col-sm-4">
                    <h3>Add an existing Student to this Course:</h3> <br>
                <table class="table-responsive">    
                    <tr>
                    <td>
                        <select class="btn-select form-control" name="studentsToAdd">
                            <?php  foreach ($listOfAllStudents as $student) : ?>
                            <option value="<?php echo htmlspecialchars($student['studentID'])?>">
                                <?php echo htmlspecialchars($student['studentID']."-".$student['studentFirstName']." ".$student['studentLastName']); ?>
                            </option>
                         <?php endforeach;?>
                        </select>
                    </td>
                    <td style="padding-top:13px; padding-left:10px;">
                        <form class="form-group" action="admin_controller.php" method="POST"> 
                            <button type="submit" class="btn btn-primary" id="save_existing_student" style="" name="action" value="saveExistingStudent">Save Selected Student</button>                    
                        </form>
                    </td>
                    </tr>
                </table>
               </div>
                <div class="col-sm-4"> <!-- DISPLAY A FORM TO ADD A NEW STUDENT TO THIS COURSE -->
                    <h3>Add a New Student:</h3> <br>
                    <form class="form-group" action="admin_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
                        <table class="table table-responsive ">
                            <tr class="form-group">
                                <td class="col-xs-1 text-left align-middle"><label for="stuID">Student ID #: </label></td>
                                <td class="col-xs-1 text-right"><input class="form-control" type="text"  name="stuID" id="student_id" style="width:150px;" onkeyup="" required></td>
                                <td><h4 class="error text-danger align-middle" id="stuIDError">*</h4></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-xs-1 text-left align-middle"><label for="chapterNum">Student First Name: </label></td>          
                                <td class="col-xs-1 text-right"><input class="form-control" type="text" name="firstName" id="first_name" style="width:150px;" required> 
                                <td><h4 class="error text-danger align-middle" id="firstNameError" >*</h4></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-xs-1 text-left align-middle"><label for="chapterNum">Student Last Name: </label></td>          
                                <td class="col-xs-1 text-right"><input class="form-control" type="text" name="lastName" id="last_name" style="width:150px;" required> 
                                <td><h4 class="error text-danger align-middle" id="lastNameError" >*</h4></td>
                            </tr>
                            <tr class="form-group pull-right">
                               <!-- <td><input type="hidden" name="action" value="saveNewStudent"</td>-->
                                <td class="pull-right"><button type="submit" class="btn btn-primary pull-right" id="save_new_student" name="action" value="saveNewStudent">Save New Student & Assign to this Course &raquo;</button><td>     
                            </tr>
                        </table>
                    </form>
                </div>             
        </div>  
        <footer>
            <p class="text-center">&copy; capstone project 2017 - bethany ann</p>
        </footer>
     <!-- end container? -->
</body>
</html>
