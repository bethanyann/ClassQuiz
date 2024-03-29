<?php
$userType = $_SESSION['userType'];
if ($userType != 'admin') { //this works!!!!!!
    header("Location: ../index.php");
    die; //redirect user to home page if they are not admin
}
?>
<!-- THIS IS THE ADMIN HOME PAGE FOR THE CLASS QUIZ site --> 
<!DOCTYPE html>
<!--AJAX function to validate the student ID doesn't exist before adding a new student -->
<script>
    //ajax function to make sure the student ID doesn't exist already
    function validateStudentID(studentID) {
        if (studentID === "" || isNaN(studentID) || studentID <= 0) {
            document.getElementById("stuIDError").innerHTML = "Please enter a valid student identification number";
            
            return;
        } 
        else if(studentID.length < 6)
        {
            document.getElementById("stuIDError").innerHTML = "Student ID Number must be 6 digits.";
           
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
            ajaxRequest.onload = function () {
                if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200)
                {
                    var result = ajaxRequest.responseText;
                    if (result === "true")
                    {
                        //if result is true that means the student ID exists in the database already and 
                        //it shouldn't be saved
                        document.getElementById("stuIDError").innerHTML = "The student ID # " + studentID + " already exists.";
                        var button = document.getElementById("save_new_student");
                        button.disabled = true;
                    } else
                    {   //passed chapter validation, now check the num questions
                        document.getElementById("stuIDError").innerHTML = "*";
                        var button = document.getElementById("save_new_student");
                        button.disabled = false;
                        
                        //can check the first and last name for validation here too maybe?
                    }
                } else { //put something here if the ajax response doesn't work
                }
            };

            var queryString = "?stuID=" + studentID;
            ajaxRequest.open("GET", "../ajax_validation_student.php" + queryString, true);
            ajaxRequest.send();
        }
    };
</script>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Manage Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../scripts/vendor/jquery-1.11.2.min.js"></script>
    <script src="../scripts/vendor/bootstrap.js"></script>
    <script src="../scripts/vendor/bootstrap.min.js"></script>
    
    <link rel="stylesheet" href="../css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/classquiz_main.css">
    <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>  
    <?php include'../shared/navigation.php';?>
    <div class="container-fluid">   
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
                        <div class="col-sm-8">    <!--DISPLAY STUDENTS ASSIGNED TO THIS COURSE -->                                      
                            <h2 class="text-center"> <?php echo $_SESSION['courseName'] ?></h2>
                            <h3>Students assigned to this course: </h3>
                                    <table class="table table-responsive"> 
                                        <thead>
                                           <th>Student ID#</th> 
                                           <th>First Name</th>
                                           <th>Last Name</th>
                                           <th>remove student from class</th>
                                           <th>edit student</th>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($listOfStudents as $student) : ?> 
                                        <tr> 
                                          <!--  <td class="col-sm-1" style="display:inline;">-->
                                            <td><?php echo htmlspecialchars($student['studentID']) ?></td>    
                                            <td><?php echo htmlspecialchars($student['studentFirstName']) ?></td>
                                            <td><?php echo htmlspecialchars($student['studentLastName']) ?></td>
                                                <form action="admin_controller.php" method="POST">
                                                    <td><button type="submit" class="btn btn-primary" name="action" value="removeStudent">remove from class</button></td>
                                                    <td><button type="submit" class="btn btn-primary" name="action" value="editStudent">edit student</button></td>
                                                <input type="hidden" name="studentID" value="<?php echo $student['studentID'] ?>">
                                                <input type="hidden" name="courseID" value="<?php echo $_SESSION['courseID'] ?>">
                                                <input type="hidden" name="studentFName" value="<?php echo $student['studentFirstName'] ?>">
                                                <input type="hidden" name="studentLName" value="<?php echo $student['studentLastName'] ?>">
                                                </form>
                                       </tr>
                                        <?php endforeach; ?>
                                        </tbody>           
                                    </table>
                                </form>
                        </div>
                <?php endif; ?><!--END OF IF STATEMENT -->  
      
                <div class="col-sm-4">
                    <h3>Add an existing Student to this Course:</h3> <br>
                <form method="POST" action="admin_controller.php">
                    <table class="table-responsive">    
                        <tr>
                            <td>
                                <select class="btn-select form-control" name="studentsToAdd">
                                <?php  foreach ($listOfAllStudents as $student) : ?>
                                    <option value="<?php echo htmlspecialchars($student['studentID'])?>">
                                     <?php echo htmlspecialchars($student['studentID']." - ".$student['studentFirstName']." ".$student['studentLastName']); ?>
                                    </option>
                                <?php endforeach;?>
                                </select>
                            </td>
                            <td style="padding-top:5px; padding-left:15px;">                     
                                <button type="submit" class="btn btn-primary" id="save_existing_student" style="" name="action" value="saveExistingStudent">Save Selected Student</button>                              
                            </td>
                        </tr>
                    </table>
                </form>
                </div>
                <div class="col-sm-4"> <!-- DISPLAY A FORM TO ADD A NEW STUDENT TO THIS COURSE -->
                    <h3>Add a New Student:</h3> <br>
                    <form class="form-group" action="admin_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
                        <table class="table table-responsive co-sm-3 ">
                            <tr class="form-group">
                                <td class="col-sm-1 text-left align-middle"><label for="stuID">Student ID #: </label></td>
                                <td class="col-xs-1 text-right"><input class="form-control" type="text"  name="stuID" id="student_id" style="width:150px;" onkeyup="validateStudentID(this.value)" required></td>
                                <td><h4 class="error text-danger align-middle" id="stuIDError">*</h4></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-sm-1 text-left align-middle"><label for="chapterNum">Student First Name: </label></td>          
                                <td class="col-xs-1 text-right"><input class="form-control" type="text" name="firstName" id="first_name" style="width:150px;" required> 
                                <td><h4 class="error text-danger align-middle" id="firstNameError" >*</h4></td>
                            </tr>
                            <tr class="form-group ">
                                <td class="col-sm-1 text-left align-middle"><label for="chapterNum">Student Last Name: </label></td>          
                                <td class="col-xs-1 text-right"><input class="form-control" type="text" name="lastName" id="last_name" style="width:150px;" required> 
                                <td><h4 class="error text-danger align-middle" id="lastNameError" >*</h4></td>
                            </tr>
                            <tr class="form-group col-sm-3 col-sm-offset-1">
                               <!-- <td><input type="hidden" name="action" value="saveNewStudent"</td>-->
                                <td><button type="submit" class="btn btn-primary pull-right" id="save_new_student" name="action" value="saveNewStudent">Save New Student & Assign to this Course &raquo;</button><td>     
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
