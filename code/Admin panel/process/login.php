<?php
session_start();

if(!isset($_POST['uname']) && !isset($_POST['password']))	return;//not set
if(empty($_POST['uname'])||empty($_POST['password']))	die("10"); //empty

if(strlen(trim($_POST['uname'])) <= 0)
{
	die("User name can't be white space");//white space
}
if(strlen(trim($_POST['password'])) <= 0)
{
	die("Password can't be white space");//white space
}

include "config.php";
$uname = strtolower(mysqli_real_escape_string($con,trim($_POST['uname'])));
$password = mysqli_real_escape_string($con,trim($_POST['password']));

$psw = $uname.$password;
$npassword=md5($psw);

$query="select `id` from `member` where `email`='".$uname."' and `password`='".$npassword."'";
$result=mysqli_query($con,$query);

if(mysqli_num_rows($result)!=1)
{
	mysqli_close($con);
	die("11");
}

$row = mysqli_fetch_assoc($result);
//create session
$ssn=md5("smartcab");
$_SESSION[$ssn] = md5($uname);
$_SESSION['unique_id'] = session_id();
$_SESSION['id'] = $row['id'];

mysqli_close($con);
die("1");
?>