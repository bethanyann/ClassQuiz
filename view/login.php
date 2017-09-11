<?php
//get username from session if there was an error
if (session_status() == PHP_SESSION_NONE) {}
else{
    $username = $_SESSION['username'];
} 
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Log In</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/classquiz_main.css">
    <script src="../scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>

<body>
<?php include'../shared/navigation.php'?>
    <div class="container-fluid">
        <div class="row">     
            <h2 class="text-center">Log In to your Account</h2>
            <div class="col-sm-6 col-sm-offset-3">
                <form action="../controller/login_controller.php" method="POST">
                    <table class="table table-responsive" id="login_table">
                        <tr>
                            <th><label>Username:</label></th>
                            <td><input type="text" class="form-control" size="25" name="username" value="<?php if(isset($username)){echo $username;}?>"/></td>
                       </tr>
                        <tr>  
                            <th><label>Password:</label></th>
                            <td><input type="password" class="form-control" size="25" name="password" value=""/></td>
                        </tr>
                        <tr>
                            <td><button type="submit" class="btn btn-primary" name="action" value="login_nav">Log In</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><span class="error_message_display"> <?php if(isset($error_message)){echo $error_message;} ?></span></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h4>First time logging in? Click <a href="">here</a> to register a new account.<h4> </td>
                        </tr>
                    </table> 
                </form>        
            </div>
        </div>
    </div>



