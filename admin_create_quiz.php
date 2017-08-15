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
    <script src="../js/main.js"></script>


</html>
<script type="text/javascript">
    $(document).ready(function()
    {    
       
    });



    $("#subtotal").change( function() {   //just for display 
        //might need to implement another event for touch screen/phone responses? $(element).on(isMobile ? 'touchend' : 'click', function(e) {...});
        var subtotal = document.getElementById("subtotal").value;
        var salestax = subtotal * .075;
        var total = Number(subtotal) + Number(salestax);
        
        document.getElementById("salestax").innerHTML = salestax;
        document.getElementById("total").innerHTML= total;
    });

    function validateInput(){
        //try to do some validation stuff here
    }

   
    function updateText() { //updates client info once a client is selected
        var selectbox = document.getElementById("client_list");
        var x = selectbox.options[selectbox.selectedIndex].value;
        var myArray = @Html.Raw(Json.Encode(Model.ListClients));
        
        var firstName = myArray[x].FirstName;
        var lastName = myArray[x].LastName;
        var street = myArray[x].BusinessStreetAddress;
        var city = myArray[x].City;
        var state = myArray[x].State;
        var zip = myArray[x].Zip;
        var clientID = myArray[x].UserID;

        document.getElementById("select_client_id").value = clientID;
        document.getElementById("contactname").innerHTML = firstName + " " + lastName;
        document.getElementById("street").innerHTML = street;
        document.getElementById("citystatezip").innerHTML = city + ", " + state + " " + zip;
    }
       

</script>