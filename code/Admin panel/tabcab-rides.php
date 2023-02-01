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

    <title>Smart cab | TABcab Rides</title>

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
            include("process/config.php");
            $rides = "SELECT r.*, cp.`name` AS `cpname`, c.`name` as `cname`, SUBSTRING_INDEX(u.`email`, '@', 1) AS `uname` FROM `rides` r, `fare` f, `company` cp, `car` c, `user` u WHERE r.`fid`=f.`id` AND r.`uid`=u.`id` AND f.`cid`=cp.`id` AND cp.`name`='TABcab' AND f.`tcid`=c.`id` ORDER BY `id` DESC";
            $run = mysqli_query($con,$rides);
        ?>
        <!-- /.navigation -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12" style="margin-top:-30px">
                    <h1 class="page-header">TABcab Rides </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row" style="margin-top:20px">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Rides Table
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Uname</th>
                                            <th>Company</th>
                                            <th>Type</th>
                                            <th>source</th>
                                            <th>Destination</th>
                                            <th>Distance</th>
                                            <th>Rate</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php  
                                            $rate=0;$i=0;
                                        while($row = mysqli_fetch_assoc($run)):
                                    ?>
                                        <tr>
                                            <td><?=$i+1;?></a></td>
                                            <td><?=$row['uname'];?></td>
                                            <td><?=$row['cpname'];?></td>
                                            <td><?=$row['cname'];?></td>            
                                            <td><?=$row['src'];?></td>
                                            <td><?=$row['dst'];?></td>
                                            <td><?=$row['dist'];?> km</td>
                                            <td>&#8377; <?=round($row['eprice']);?><?php $rate=$rate+$row['eprice'];?></td>                       
                                            <td>
                                                <?php 
                                                    $date = strtotime($row['date']);
                                                    $day   = date('d',$date);
                                                    $month = date('m',$date);
                                                    $year  = date('Y',$date);
                                                    echo $day."-".$month."-".$year;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php 
                                        $i++;   
                                        endwhile; 
                                        mysqli_close($con);
                                    ?>
                                        <tr style="color:blue;">
                                            <td>Total</td>
                                            <td><?=$i;?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>&#8377; <?=round($rate);?></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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