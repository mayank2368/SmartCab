<?php
    session_start();
    //session validation
    require("BaseUrl.php");
    $url = url();
    $ssn_id = session_id();
    $ssn=md5("smartcab");
    if(isset($_SESSION[$ssn]) && isset($_SESSION['unique_id']) && isset($_SESSION['id']))
    { 
        //retrive user email to validate session
        include("config.php");
        $id = mysqli_real_Escape_string($con,$_SESSION['id']);
        $querySsn = "select * from `member` where `id`=$id";
        $querySsn_run = mysqli_query($con,$querySsn);
        $querySsn_row = mysqli_fetch_assoc($querySsn_run);
        mysqli_close($con);    
        
        $email = $querySsn_row['email'];
        $email_valid = md5($email);
        if($_SESSION['unique_id']==$ssn_id && $_SESSION[$ssn]==$email_valid)
        {
            $resJson = ['hasError'=>0];
        	if(!isset($_POST['id']) || !isset($_POST['car'])){
                $resJson['hasError']++;
                $resJson['msg'] = 'Field is not set!';
                print_r(json_encode($resJson)); 
                exit; 
            } 
            if(empty($_POST['id']) || empty($_POST['car'])){
                $resJson['hasError']++;
                $resJson['msg'] = 'Field value cannot be empty!';
                print_r(json_encode($resJson)); 
                exit; 
            } 
            if(strlen(trim($_POST['id'])) <= 0){
                $resJson['hasError']++;
                $resJson['msg'] = 'Company ID cannot be white space!';
                print_r(json_encode($resJson)); 
                exit; 
            }  
            if(strlen(trim($_POST['car'])) <= 0){
                $resJson['hasError']++;
                $resJson['msg'] = 'Types of car must be selected!';
                print_r(json_encode($resJson)); 
                exit; 
            } 

        	include("config.php");
        	$id=mysqli_real_escape_string($con,$_POST['id']);
        	$car=mysqli_real_escape_string($con,$_POST['car']);

        	$query = "SELECT *  FROM `fare` WHERE `cid`=$id AND `tcid`=$car";
            $run = mysqli_query($con,$query);
            if(mysqli_num_rows($run) != 1){
                $resJson['hasError']++;
                $resJson['msg'] = 'Fare does not exist!';
                print_r(json_encode($resJson)); 
                mysqli_close($con);
                exit; 
            }

            $row = mysqli_fetch_assoc($run);
            mysqli_close($con);
            $resJson['price'] = $row['price'];
            $resJson['date'] = $row['date'];
            print_r(json_encode($resJson));
            exit; 
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