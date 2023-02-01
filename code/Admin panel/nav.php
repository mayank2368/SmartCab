<?php
    //session validation
    $ssn=md5("smartcab");
    if(isset($_SESSION[$ssn]) && isset($_SESSION['unique_id']) && isset($_SESSION['id']))
    { 
        $ssn_id = session_id();
     
        //retrive user email to validate session
        include("process/config.php");
        $id = mysqli_real_Escape_string($con,trim($_SESSION['id']));
        $queryNavSsn = "select * from `member` where `id`=$id";
        $queryNavSsn_run = mysqli_query($con,$queryNavSsn);
        $queryNavSsn_row = mysqli_fetch_assoc($queryNavSsn_run);
        mysqli_close($con);    
        
        $email = $queryNavSsn_row['email'];
        $email = md5($email);
        if($_SESSION['unique_id']==$ssn_id && $_SESSION[$ssn]==$email)
        {
?>
<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=$url;?>">Smart Cab</a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
               
                <!-- /.dropdown -->
                <?=$queryNavSsn_row['name'];?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?=$url;?>/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
            </ul>
            <!-- /.navbar-top-links -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search" style="padding-top:3px;padding-bottom:3px;">
                            <div class="input-group custom-search-form">
                                <form method="get" action="machine.php">
                                <input type="text" class="form-control" style="width:80%;" name="id" placeholder="Search...">
                                <span class="input-group-btn">
	                                <button class="btn btn-default" type="submit">
	                                    <i class="fa fa-search"></i>
	                                </button>
                                </span>
                                </form>
                            </div><!-- /input-group -->
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-edit fa-fw"></i> Uber <span class="fa arrow"></span></a>
                             <ul class="nav nav-second-level">
                                <li>
                                    <a href="uber-rate.php"> <i class="fa fa-edit fa-fw"></i> Update Rate</a>
                                </li>
                                <li>
                                    <a href="uber-rides.php"> <i class="fa fa-table fa-fw"></i> View Rides</a>
                                </li>
                            </ul>
                        </li>             
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-edit fa-fw"></i> Ola cab<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="ola-rate.php"> <i class="fa fa-edit fa-fw"></i> Update Rate</a>
                                </li>
                                <li>
                                    <a href="ola-rides.php"> <i class="fa fa-table fa-fw"></i> View Rides</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-edit fa-fw"></i> TABcab <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="tabcab-rate.php"> <i class="fa fa-edit fa-fw"></i> Update Rate</a>
                                </li>
                                <li>
                                    <a href="tabcab-rides.php"> <i class="fa fa-table fa-fw"></i> View Rides</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-edit fa-fw"></i> Users <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="all-users.php"> <i class="fa fa-edit fa-fw"></i> View all</a>
                                </li>
                            </ul>
                        </li> 
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav> <!-- /.navigation -->
<?php
        }
        else
        {
            session_unset();
            session_destroy();
            echo "<script>window.location.href='$url'</script>";
        }
    }
    else
    {
        echo "<script>window.location.href='$url'</script>";
    }
?>