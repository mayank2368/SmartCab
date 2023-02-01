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

    <title>Smart cab | TABcab Rate</title>

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

    <style>
    .error
    {
        width:100%; 
        margin-bottom:10px; 
        font-size:13px; 
        padding-left:10px !important;
        background-color:red !important;
        color:#ffffff !important;
    }
    .error_data
    {
        padding : 3px;
        background-color: white;
        color:green; 
    }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php 
            include("nav.php");
            include("process/config.php");

            $query = "select * from `company` where name='TABcab'";
            $run = mysqli_query($con,$query);
            $row = mysqli_fetch_assoc($run);

            $query = "SELECT * FROM `car`";
            $run = mysqli_query($con,$query);
            mysqli_close($con);
         ?>
        <!-- /.navigation -->

        <div id="page-wrapper">
            <div class="row" style="padding-top:20px;">
                <div class="col-lg-12">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            Update Fare Rate
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="update">
                                        
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input class="form-control" type="text" value="<?=$row['name'];?>" name="name" id="name" disabled required>
                                        </div>
                                        <div class="form-group">
                                            <label>CAR type</label>
                                            <select class="form-control" name="car" id="car" required>
                                                <option value="">Select car type</option>
                                                <?php
                                                    while($car = mysqli_fetch_assoc($run)):
                                                ?>
                                                    <option value="<?=$car['id'];?>"><?=$car['name'];?> (<?=$car['info'];?>)</option>
                                                <?php
                                                    endwhile;  
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Updated on <label style="color:grey;margin: 1px 5px; font-size: 11px;">(yyyy-dd-mm hh:mm:ss)</label></label>
                                            <input class="form-control" type="text" name="date" id="date"  disabled required>
                                        </div> 
                                        <div class="form-group">
                                            <label>Fare Rate<label style="color:grey;margin: 1px 5px; font-size: 11px;">(per km)</label></label>
                                            <input type="hidden" name="id" value="<?=$row['id'];?>" />
                                            <input class="form-control" type="text" placeholder="Eg. 3.65" name="price" id="price" required onkeypress="return onlyDecimalNumeric(event,this);"> 
                                        </div>
                                        <div class="form-group">
                                            <div class="error_data" id="error_data"></div>
                                        </div>
                                        <button type="button" class="btn btn-default" id="submit">Update Rate</button>
                                        <br><br><br>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script src="js/input_validate.js"></script>
    <script src="js/rate.js"></script>
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