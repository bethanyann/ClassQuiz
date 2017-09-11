<?php 
if(isset($_SESSION['usernameLoggedIn']))
{
    $loggedInStatus = 1;
}
else {
    $loggedInStatus = 0;
}
?>
<nav class="navbar navbar-default" style="margin-bottom:0px;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if($loggedInStatus === 0) : ?>
                <li class=""><a href="view/login.php?action=login">Log In</a></li>
                <?php else: ?>
                <li class=""><a href="view/login.php?action=logout">Log Out</a></li>
                <?php endif; ?>
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
<!--            <form class="navbar-form navbar-right" action="ClassQuiz/controller/login_controller.php" method="POST">
                <div class="form-group">
                    <input type="text" placeholder="User ID" name="username" class="form-control" style="width:100px;">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" class="form-control">
                </div>
                    <button type="submit" class="btn btn-success" name="action" value="login_nav">Sign in</button> 
            </form>-->

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>     