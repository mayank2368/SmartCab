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
        $id = mysqli_real_escape_string($con,$_SESSION['id']);
        $querySsn = "select * from `member` where `id`=$id";
        $querySsn_run = mysqli_query($con,$querySsn);
        $querySsn_row = mysqli_fetch_assoc($querySsn_run);
        mysqli_close($con);    
        
        $email = $querySsn_row['email'];
        $email_valid = md5($email);
        if($_SESSION['unique_id']==$ssn_id && $_SESSION[$ssn]==$email_valid)
        {
            echo "<script>window.location.href='$url/home.php'</script>";
        }
        else
        {
            session_unset();
            session_destroy();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Smartcab">
    <link rel="shortcut icon"  href="images/smartcab.svg" />
    <title>Smart Cab | Login</title>
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
    .error
    {
        width:100%; 
        margin-bottom:10px; 
        font-size:13px; 
        padding-left:10px !important;
        background-color:rgb(213, 50, 50);
        color:#ffffff !important;
    }
    .error_data
    {
        padding : 3px;
    }
    a
    {
        text-decoration:none !important;
        color:rgba(0,0,0,0.7) !important;
    }
    a:focus 
    {
        outline: none !important;
        outline-offset: none !important;
    }
    .neptune_header
    {
        text-align:center; 
        font-size: 500%; 
        margin-top: 5%; 
        margin-bottom: -3%; 
        color:rgba(0,0,0,0.7);
    }
    </style>
</head>
<body background="images/bg.jpg" style="background-size:100%;" >
    <div class="container">
        <div class="row">
         <div class="neptune_header"><img src="images/smartcab.svg" height="70px" style="margin-bottom: 20px;"/><a style="margin-left:5px;" href="<?=$url?>">Smart Cab</a></div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">                
                <div class="login-panel panel panel-default" style="background-color:rgba(0,0,0,0.1); border-color:rgba(0,0,0,0.3);">
                    <div class="panel-heading" style="background-color:rgba(0,0,0,0.1); color:rgba(0,0,0,0.9); border-color:rgba(0,0,0,0.3);">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form id="form_login">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User Name" name="uname" type="email" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="password" name="password" type="password" required>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->                
                                <div class="error_data"></div>
                            </fieldset>
                            <input type="submit" value="Login" id="submit_login" class="btn btn-lg btn-success btn-block">
                        </form>     
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>  
    <script src="js/login.js"></script>
</body>
</html>