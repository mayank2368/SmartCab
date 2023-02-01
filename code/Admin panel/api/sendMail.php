<?php
    $resJson = ['hasError'=>0];
    if(!isset($_POST['email']) || !isset($_POST['name'])  || !isset($_POST['subject'])  || !isset($_POST['message'])){
        echo  'Field is not set!';
        exit; 
    }
    if(empty($_POST['email']) || empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message'])){
        echo 'Fields value cannot be empty!';
        exit; 
    } 
    if(strlen(trim($_POST['email'])) <= 0){
        echo 'Email cannot be white space!'; 
        exit; 
    } 
    if(strlen(trim($_POST['name'])) <= 0){
        echo 'Name cannot be white space!';
        exit; 
    } 
    if(strlen(trim($_POST['subject'])) <= 0){
        echo 'Subject cannot be white space!';
        exit; 
    } 
    if(strlen(trim($_POST['message'])) <= 0){
        echo 'Message cannot be white space!';
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
    $name = mysqli_real_escape_string($con,trim($_POST['name']));
    $sub = mysqli_real_escape_string($con,trim($_POST['subject']));
    $msg = mysqli_real_escape_string($con,trim($_POST['message']));
    $email = mysqli_real_escape_string($con,trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));

    $subject = "Contact From: ".$name;
    $headers  = 'MIME-Version: 1.0' . "\r\n";

      $headers .= 'From: '. $name . '<' . $email . '>' . "\r\n";
    $headers .= 'Reply-To: ' . $email . "\r\n";
      $headers .= "CC: ".$email."\r\n";
    
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        //begin of HTML message 
    $message = '
    <html>
    <head>
      <title>Contact form</title>
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
    <p>Dear Smart Cab team</p>
      <p>Contact From : </p>
      <div><b>Name : </b>'.$name.'</div>
      <div><b>Email : </b>'.$email.'</div>
      <div><b>Subject : </b>'.$sub.'</div>
      <div><b>Message : </b>'.$msg.'</div>
        <br><br>

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

    if(!mail('smartcab22@gmail.com', $subject, $message, $headers)){
        echo "email not send due to some reason!"; 
    }

    echo "true"
?>