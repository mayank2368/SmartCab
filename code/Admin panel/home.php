<?php
    session_start();
    //session validation
    require("process/BaseUrl.php");
    $url = url();
    $ssn_id = session_id();
    $ssn=md5("smartcab");
    if(isset($_SESSION[$ssn]) && isset($_SESSION['unique_id']) && isset($_SESSION['id']))
    { 
        //retrive user email to validate session
        include("process/config.php");
        $id = mysqli_real_Escape_string($con,$_SESSION['id']);
        $querySsn = "select * from `member` where `id`=$id";
        $querySsn_run = mysqli_query($con,$querySsn);
        $querySsn_row = mysqli_fetch_assoc($querySsn_run);
        mysqli_close($con);    
        
        $email = $querySsn_row['email'];
        $email_valid = md5($email);
        if($_SESSION['unique_id']==$ssn_id && $_SESSION[$ssn]==$email_valid)
        {
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Techsperia">
    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />

    <title>Smart cab | home</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <?php 
            include("nav.php"); 
        ?>
        <!-- /.navigation -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12" style="margin-top:-30px">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-taxi fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="medium">
                                    <?php
                                        include("process/config.php"); 
							            $fare = "SELECT SUM(`eprice`) AS `price` FROM `rides` where `fid` IN (SELECT `id` FROM `fare` WHERE `cid`=2)";
							            $run = mysqli_query($con,$fare);
                                        $row = mysqli_fetch_assoc($run);
							            echo "<h4>&#8377; ".round($row['price'])."</h4>";
                                    ?> 
                                   	</div>
                                    <div>Uber</div>
                                </div>
                            </div>
                        </div>
                        <a href="uber-rides.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Rides</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-taxi fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">
                                    <?php
                                        $fare = "SELECT SUM(`eprice`) AS `price` FROM `rides` where `fid` IN (SELECT `id` FROM `fare` WHERE `cid`=1)";
                                        $run = mysqli_query($con,$fare);
                                        $row = mysqli_fetch_assoc($run);
                                        echo "<h4>&#8377; ".round($row['price'])."</h4>";
                                    ?> 
                                    </div>
                                    <div>Ola cab</div>
                                </div>
                            </div>
                        </div>
                        <a href="ola-rides.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Rides</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-taxi fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">
                                    <?php
                                        $fare = "SELECT SUM(`eprice`) AS `price` FROM `rides` where `fid` IN (SELECT `id` FROM `fare` WHERE `cid`=3)";
                                        $run = mysqli_query($con,$fare);
                                        $row = mysqli_fetch_assoc($run);
                                        echo "<h4>&#8377; ".round($row['price'])."</h4>";
                                    ?>
                                    </div>
                                    <div>TABcab</div>
                                </div>
                            </div>
                        </div>
                        <a href="tabcab-rides.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Rides</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-taxi fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">
                                    <?php
                                        $fare = "SELECT SUM(`eprice`) AS `price` FROM `rides`";
                                        $run = mysqli_query($con,$fare);
                                        $row = mysqli_fetch_assoc($run);
                                        echo "<h4>&#8377; ".round($row['price'])."</h4>";
                                    ?> 
                                    </div>
                                    <div>All cab</div>
                                </div>
                            </div>
                        </div>
                        <a href="all-rides.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Rides</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div> <!-- /#row -->
        </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
</body>

</html>
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