<?php if(isset($_SESSION['adminCourses']))
{      $adminCourses = $_SESSION['adminCourses'];       } 
?>
<div class="col-sm-2 admin_nav " style="padding-left:0px;min-height:100%;">
    <h4 class="text-center">Admin Menu</h4>
        <ul id="menu">                  
            <li class="" style="padding-left:10px;"><a href="../controller/admin_controller.php?action=adminDashboard"> <span class="link-title text-center">&nbsp;Dashboard</span></a></li>
        <!--<li class="" style="padding-left:10px;"><a href=""> <span class="link-title">&nbsp;Manage Students</span></a></li>
            <li class="" style="padding-left:10px;"><a href=""> <span class="link-title">&nbsp;Manage Account</span></a></li> -->              
            <li class="dropdown" style="padding-left:10px;">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Quizzes<span class="caret"></span></a>
                    <ul class="dropdown-menu" style="font-size:.8em" style="padding-left:10px;">
                        <?php foreach ($adminCourses as $course) : ?>
                        <?php $queryString = "?action=viewQuizzes". "&courseID=" . $course['courseID'] . "&courseName=" . $course['courseName'];?>
                            <li style="padding-left:7px;"><a href="../controller/admin_controller.php<?php echo $queryString ?>"><?php  echo $course['courseID']."-".$course['courseName']; ?></a></li>
                            <li role="separator" class="divider" style="padding:0px;"></li>
                        <?php endforeach; ?>        
                    </ul>
            </li>         
        </ul>
</div>          
