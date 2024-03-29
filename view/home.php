<!DOCTYPE html>
<!-- THIS IS THE MAIN HOME PAGE FOR THE CLASS QUIZ site --> 
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Class Quiz Homepage</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">   
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/classquiz_main.css">
        <script src="scripts/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
    
<body>
    <?php include 'shared/navigation_homepage.php'?>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" >       
        <div class="container">
            <h1></h1>
        </div>
        <p> <br><br><br><br><br><br><br><br> </p>
    </div>

    <div class="container">
<!-- Example row of columns -->
      <div class="row">
       <div class="col-md-4">
          <h2> Heading </h2>
          <p> This is just filler information here. </p>
          <p><a class="btn btn-default" role="button">Go &raquo;</a></p>  
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p> This is just filler information here. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p> This is just filler information here. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>

      <hr>
 
    </div> <!-- /container -->    
   
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
        <script src="scripts/vendor/bootstrap.min.js"></script>
        <script src="../scripts/classquiz.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be my site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
