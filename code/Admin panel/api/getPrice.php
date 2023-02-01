<?php
    $resJson = ['hasError'=>0];
    if(!isset($_POST['origin']) || !isset($_POST['destination']) || !isset($_POST['email']) || !isset($_POST['tid'])){
        $resJson['hasError']++;
        $resJson['error'] = 'Field is not set!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 
    if(empty($_POST['origin']) || empty($_POST['destination']) || empty($_POST['email']) || empty($_POST['tid'])){
        $resJson['hasError']++;
        $resJson['error'] = 'Fields value cannot be empty!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 
    if(strlen(trim($_POST['origin'])) <= 0){
        $resJson['hasError']++;
        $resJson['error'] = 'Origin cannot be white space!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }  
    if(strlen(trim($_POST['destination'])) <= 0){
        $resJson['hasError']++;
        $resJson['error'] = 'Destination cannot be white space!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 
    if(strlen(trim($_POST['email'])) <= 0){
        $resJson['hasError']++;
        $resJson['error'] = 'Email cannot be white space!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    } 
    if(strlen(trim($_POST['tid'])) <= 0){
        $resJson['hasError']++;
        $resJson['error'] = 'Type ID cannot be white space!';
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
    $email = mysqli_real_escape_string($con,trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
    $origin = mysqli_real_escape_string($con,trim($_POST['origin']));
    $destination = mysqli_real_escape_string($con,trim($_POST['destination']));
    $tid = mysqli_real_escape_string($con,trim($_POST['tid']));
    $key = "AIzaSyAW1n8vtFckqBym8CWvBfPVhsU8KpefZvo";
    $parameters="units=imperial&origins=".$origin."&destinations=".$destination."&key=".$key;

    $query = "SELECT `id` FROM `user` WHERE `email`='$email'";
    $run = mysqli_query($con,$query);
    if(mysqli_num_rows($run) != 1){
        mysqli_close($con);
        $resJson['hasError']++;
        $resJson['error'] = 'User does not exist!';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }
    $row = mysqli_fetch_assoc($run);
    $id = $row['id'];

    $getData = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?'.$parameters, false));
    if($getData->status == "OK" && $getData->rows[0]->elements[0]->status == "OK"){

        $query = "SELECT `id`,`price` FROM `fare` WHERE `tcid`='$tid'  ORDER BY `cid` ASC";
        $run = mysqli_query($con,$query);
        if(!mysqli_num_rows($run)){
            mysqli_close($con);
            $resJson['hasError']++;
            $resJson['error'] = 'Car Type dooes not exist!';
            print_r(json_encode(array("response"=>($resJson)))); 
            exit; 
        }
        $data = array();
        while($row = mysqli_fetch_assoc($run))
            array_push($data,$row); 
        $distance = round((($getData->rows[0]->elements[0]->distance->value)/1000),1);
        $origin = $getData->destination_addresses[0];
        $destination = $getData->origin_addresses[0];
        $duration = round((($getData->rows[0]->elements[0]->duration->value)/60),0);

        $query = "INSERT INTO `viewrides`(`uid`, `src`, `dst`, `dist`,`time`) VALUES ($id,'$origin','$destination',$distance,$duration)";
        if(!mysqli_query($con,$query)){
            mysqli_close($con);
            $resJson['hasError']++;
            $resJson['error'] = 'Rides not viewed due to some reason!';
            print_r(json_encode(array("response"=>($resJson)))); 
            exit; 
        }

        $resJson["msg"]= array(
            "origin"        =>   $origin,
            "destination"   =>   $destination,
            "distance"      =>   $distance,
            "duration"      =>   $duration,
            "ola"=>     array(
                "id"    =>   $data[0]['id'],
                "rate"  =>   round($data[0]['price']*$distance)
            ),  
            "uber"=>    array(
                "id"    =>   $data[1]['id'],
                "rate"  =>   round($data[1]['price']*$distance)
            ),
            "tabcab"=>  array(
                "id"    =>   $data[2]['id'],
                "rate"  =>   round($data[2]['price']*$distance)
            )
        );

        mysqli_close($con);
        print_r(json_encode(array("response"=>($resJson)))); 
        exit;     

    }
    else{
        $resJson['hasError']++;
        $resJson['error'] = 'Requested data is invalid! :(';
        print_r(json_encode(array("response"=>($resJson)))); 
        exit; 
    }
?>