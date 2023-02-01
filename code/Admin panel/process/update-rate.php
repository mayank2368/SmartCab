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
        	if(!isset($_POST['id']) || !isset($_POST['price']) || !isset($_POST['car'])){
                $resJson['hasError']++;
                $resJson['msg'] = 'Field is not set!';
                print_r(json_encode($resJson)); 
                exit; 
            } 
            if(empty($_POST['id']) || empty($_POST['price'])  || empty($_POST['car'])){
                $resJson['hasError']++;
                $resJson['msg'] = 'Field value cannot be empty!';
                print_r(json_encode($resJson)); 
                exit; 
            } 
            if(strlen(trim($_POST['id'])) <= 0){
                $resJson['hasError']++;
                $resJson['msg'] = 'ID cannot be white space!';
                print_r(json_encode($resJson)); 
                exit; 
            }  
            if(strlen(trim($_POST['price'])) <= 0){
                $resJson['hasError']++;
                $resJson['msg'] = 'Price cannot be white space!';
                print_r(json_encode($resJson)); 
                exit; 
            } 
            if(strlen(trim($_POST['car'])) <= 0){
                $resJson['hasError']++;
                $resJson['msg'] = 'Type of cr must be selected!';
                print_r(json_encode($resJson)); 
                exit; 
            } 

        	include("config.php");
        	$id = mysqli_real_escape_string($con,$_POST['id']);
        	$price = mysqli_real_escape_string($con,$_POST['price']);
            $car = mysqli_real_escape_string($con,$_POST['car']);

        	$checkQuery = "SELECT `id`  FROM `fare` WHERE `cid`=$id AND `tcid`=$car";
            $checkQuery_run = mysqli_query($con,$checkQuery);
            if(mysqli_num_rows($checkQuery_run) != 1){
                $resJson['hasError']++;
                $resJson['msg'] = 'Fare does not exist!';
                print_r(json_encode($resJson)); 
                mysqli_close($con);
                exit; 
            }

            $updateQuery = "UPDATE `fare` SET `price`=ROUND($price,1),`date`=now() WHERE `cid`=$id AND `tcid`=$car";
            if(!mysqli_query($con,$updateQuery)){   
                $resJson['hasError']++;
                $resJson['msg'] = mysqli_msg($con);
                print_r(json_encode($resJson)); 
                mysqli_close($con);
                exit; 
            }

            $query = "SELECT `date`,`price` FROM `fare` WHERE `cid`=$id AND `tcid`=$car";
            $run = mysqli_query($con,$query);
            $row = mysqli_fetch_assoc($run);
            $resJson['price'] = $row['price'];
            $resJson['date'] = $row['date'];
            mysqli_close($con);
            $resJson['msg'] = 'Fare Rate successfully updated!';

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