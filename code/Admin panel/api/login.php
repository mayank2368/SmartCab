<?php
    $resJson = ['hasError'=>0];
    if(!isset($_POST['email']) || !isset($_POST['password'])){
        echo 'Field is not set!'; 
        exit; 
    } 
    if(empty($_POST['email']) || empty($_POST['password'])){
        echo 'Fields value cannot be empty!';
        exit; 
    } 
    if(strlen(trim($_POST['email'])) <= 0){
        echo 'Email cannot be white space!';
        exit; 
    } 
    if(strlen(trim($_POST['password'])) <= 0){
       	echo 'Password cannot be white space!'; 
        exit; 
    }

    // Remove all illegal characters from email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    //Validate e-mail
    if(!filter_var($email, FILTER_VALIDATE_EMAIL) == true){
        echo 'Email id is not valid!';
        exit; 

    }

    include("../process/config.php");
    $email = mysqli_real_escape_string($con,trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
    $password = mysqli_real_escape_string($con,trim($_POST['password']));
    $md5pass = md5($email.$password);

    $query = "SELECT `id` FROM `user` WHERE `email`='$email' AND `password`='$md5pass'";
    $run = mysqli_query($con,$query);
    if(mysqli_num_rows($run) != 1){
        mysqli_close($con);
        echo 'Incorrect username and password!';
        exit; 
    }

    mysqli_close($con);
    echo 'true';
    exit;
?>