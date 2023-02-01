<?php
    $resJson = ['hasError'=>0];
    if(!isset($_POST['email']) || !isset($_POST['fid'])){
        $resJson['hasError']++;
        $resJson['error'] = 'Field is not set!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }
    if(empty($_POST['email']) || empty($_POST['fid'])){
        $resJson['hasError']++;
        $resJson['error'] = 'Fields value cannot be empty!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 
    if(strlen(trim($_POST['email'])) <= 0){
        $resJson['hasError']++;
        $resJson['error'] = 'Email cannot be white space!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 
    if(strlen(trim($_POST['fid'])) <= 0){
        $resJson['hasError']++;
        $resJson['error'] = 'Fare ID cannot be white space!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 

    // Remove all illegal characters from email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    //Validate e-mail
    if(!filter_var($email, FILTER_VALIDATE_EMAIL) == true){
        $resJson['hasError']++;
        $resJson['error'] = 'Email id is not valid!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }

    include("../process/config.php");
    $fid = mysqli_real_escape_string($con,trim($_POST['fid']));
    $email = mysqli_real_escape_string($con,trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));

    $query = "SELECT `id`, SUBSTRING_INDEX(`email`, '@', 1) AS `uname` FROM `user` WHERE `email`='$email'";
    $run = mysqli_query($con,$query);
    if(mysqli_num_rows($run) != 1){
        mysqli_close($con);
        $resJson['hasError']++;
        $resJson['error'] = 'User does not exist!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }
    $row = mysqli_fetch_assoc($run);
    $uid = $row['id'];
    $uname = $row['uname'];
    
    $query = "SELECT vr.*,ROUND(f.`price`*vr.`dist`) AS `eprice`, cp.`name` as cpname, c.`name` as cname FROM `viewrides` vr, fare f, company cp, car c WHERE f.`id`=$fid AND f.`cid`=cp.`id` AND f.`tcid`=c.`id` AND vr.`id`=(SELECT MAX(`id`) FROM `viewrides` WHERE `uid`=$uid)";
    $run = mysqli_query($con,$query);
    if(mysqli_num_rows($run) != 1){
        mysqli_close($con);
        $resJson['hasError']++;
        $resJson['error'] = 'Ride not booked due to invalid data!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }
    $row = mysqli_fetch_assoc($run);

    $carNo = "MH 04 DE ".rand(100,9999);
    $mbNo = "+919987".rand(000000,999999);
    $min = rand(20,80);

    $query = "INSERT INTO `rides`(`src`, `dst`, `fid`, `eprice`, `date`, `uid`, `dist`, `time`, `carNo`, `mbNo`, `calDate`) VALUES ('".$row['src']."','".$row['dst']."',$fid,".$row['eprice'].",now(),$uid,".$row['dist'].",".$row['time'].", '$carNo', '$mbNo',now()+INTERVAL ".$min." MINUTE)";
    if(!mysqli_query($con,$query)){
        mysqli_close($con);
        $resJson['hasError']++;
        $resJson['error'] = 'Ride not booked due to some reason!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }

    $query = "DELETE FROM `viewrides` WHERE `id`=".$row['id'];
    mysqli_query($con,$query);

    $to   = $email;
    $subject ="Ride Booked";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Additional headers
    $headers .= 'From: Smart Cab <smartcab22@gmail.com>' . "\r\n";
      $headers .= "CC: smartcab22@gmail.com\r\n";
        //begin of HTML message 
    $message = '
    <html>
    <head>
      <title>Ride Booked!</title>
      <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
      <style>
        button
        {
            border-width:2px;
            border-color:white;
            display:block;
            padding:5px;
            background : #062D53;
            cursor:pointer;
            float:left;
            margin-left:15px;
            color:white;
            text-decoration:none;
        }
        button:hover
        {
            background: rgba(0,0,0,0.9);
            color:white;
        }
      </style>
    </head>
    <body>
    <div style="width:100%; background-color:#eae5e5; color:black;">
    <div style="background-color:#eae5e5; float:left; width:100%; padding:10px; float:left;">
      <div style="float:left; width:70%;">
        <div style="float:left"><img src="https://webstermind.000webhostapp.com/images/favicon.png" height=30px;></div>
        <div style="float:left; margin-left:10px; color:#339AE5; font-size:30px;">Smart Cab</div>
      </div>
    </div>
    <div style=" padding:5px; padding-top:10px; width:100%; float:left; background-color:white; color:black;">
    Dear '.$uname.',
      <p>Thank you for booking ride at Smart Cab. </p><br>
      <div><b>Source : </b>'.$row["src"].'</div>
      <div><b>Destination : </b>'.$row["dst"].'</div>
      <div><b>Company : </b>'.$row["cpname"].'</div>
      <div><b>Car Type : </b>'.$row["cname"].'</div>
      <div><b>Estimated Distance : </b>'.$row["dist"].' km</div>
      <div><b>Estimated Price : </b>&#8377; '.$row["eprice"].' </div>
      <div><b>Estimated time : </b>'.$row["time"]. 'min</div>
        <br>
       <p>If you are experiencing any technical difficulties, please send an email to <a href="mailto:smartcab22@gmail.com">smartcab22@gmail.com</a> and we will get back to you as soon as possible.</p>
    <br><br><br>
    Regards,<br>
    Smart Cab Team<br><br>
    </div>

    <footer>
    <div style=" width:100%; float:left; padding:10px; padding-top:5px; background-color:#eae5e5;">
      <div style="width:50%; float:left;">
         <span style="font-size:120%;">Contact Us</span>
          <address style="margin-top:10px;">
            <span >Irla, N. R. G Marg, </span><br>
            <span >Vileparle (W), Mumbai: </span><span style=" text-decoration:none;">400056.</span><br/>
            <abbr title="Telephone" >Telephone: </abbr> <span style=" text-decoration:none;">+917977178583</span><br/>
            <abbr title="Email" >Email: </abbr> <a href="mailto:smartcab22@gmail.com" >smartcab22@gmail.com</a>
          </address>
      </div>
      <div style="float:left; width:50%;">
        <table>
            <tr>
            <td><p style="padding-right:20px; color:black;">Follow us:</p></td>
            <td style="margin-left:15px;"> 
            <a href="https://twitter.com/"  style="text-decoration:none;"><img src="https://webstermind.000webhostapp.com/images/twitter.png" height=20px; style="margin-top:-2%; padding:10px; padding-left:0px;"></a>
            </td>
            <td>
            <a href="https://www.facebook.com/"  style="text-decoration:none;"><img src="https://webstermind.000webhostapp.com/images/facebook.png" height=20px; style="margin-top:-2%; padding:10px; padding-left:0px;"></a>
            </td>
            <td>
            <a href="https://plus.google.com/"  style="text-decoration:none;"><img src="https://webstermind.000webhostapp.com/images/google-plus.png" height=20px; style="margin-top:-2%; padding:10px; padding-left:0px;"></a>
            </td>
            <td>
            <a href="https://www.pinterest.com/" style="text-decoration:none;"><img src="https://webstermind.000webhostapp.com/images/pinterest.png" height=20px; style="margin-top:-2%; padding:10px; padding-left:0px;"></a>
            </td>
        </table>
      </div>
    </div>
    </footer>
    </div>
    </body>
    </html>
    ';

    if(!mail($to, $subject, $message, $headers)){
        mysqli_close($con);
        $resJson['hasError']++;
        $resJson['error'] = 'Ride booked but mail does not send due to some reason!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 

    }

    mysqli_close($con);
    $resJson['msg'] = "Your Ride has been successfully booked !";
    print_r(json_encode(array("response"=>($resJson))));
    exit;
?>