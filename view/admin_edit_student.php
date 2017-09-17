<?php
$userType = $_SESSION['userType'];
if ($userType != 'admin') { //this works!!!!!!
    header("Location: ../index.php");
    die; //redirect user to home page if they are not admin
}
?>
<!DOCTYPE html>
<!-- EDIT A STUDENT  -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Edit Student</title>
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
    <?php include'../shared/navigation.php'; ?>
    <div class="container-fluid">   
        <div class="row">
            <?php include '../shared/admin_navigation.php'; ?>
            <div class="col-sm-5">
                <h3>Edit existing student's info: </h3> <br>
                <form class="form-group" action="admin_controller.php" method="POST"> <!--building a new quiz directs to the quiz controller-->
                    <table class="table table-responsive">
                        <tr class="form-group">
                            <td class="col-sm-1 text-left align-middle"><label for="stuID">Student ID #: </label></td>
                            <td class="col-xs-1 text-right"><input class="form-control" type="text"  name="stuID" id="student_id" style="width:150px;" disabled="disabled" required value="<?php echo $_SESSION['studentID']; ?>"></td>
                            <td><h4 class="error text-danger align-middle" id="stuIDError">*</h4></td>
                        </tr>
                        <tr class="form-group ">
                            <td class="col-sm-1 text-left align-middle"><label for="chapterNum">Student First Name: </label></td> 
                            <td class="col-xs-1 text-right"><input class="form-control" type="text" name="firstName" id="first_name" style="width:150px;" required value="<?php echo $studentFirstName; ?>"> 
                            <td><h4 class="error text-danger align-middle" id="firstNameError" >*</h4></td>
                        </tr>
                        <tr class="form-group ">
                            <td class="col-sm-1 text-left align-middle"><label for="chapterNum">Student Last Name: </label></td>          
                            <td class="col-xs-1 text-right"><input class="form-control" type="text" name="lastName" id="last_name" style="width:150px;" required value="<?php echo $studentLastName; ?>"> 
                            <td><h4 class="error text-danger align-middle" id="lastNameError" >*</h4></td>
                        </tr>
                        <tr class="form-group col-sm-3 col-sm-offset-1">
                           <!-- <td><input type="hidden" name="action" value="saveNewStudent"</td>-->
                            <td><button type="submit" class="btn btn-primary pull-right" id="save_new_student" name="action" value="saveEditStudent">Save Student Info &raquo;</button><td>     
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
