<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["title"])&&isset($_POST["date"])&&isset($_POST["time"])&&isset($_POST["address"])&&isset($_POST["card_id"])&&isset($_POST["lat"])&&isset($_POST["lng"])){
    $title = $_POST["title"];
    $datetime = $_POST["date"]." ".$_POST["time"];
    $address = $_POST["address"];
    $card_id = $_POST["card_id"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $res = array();
    $res_final = TRUE;

    if(isset($_POST["topic"])) 
        $topic = $_POST["topic"];  
    else
        $topic = NULL; 
    
    
    mysqli_autocommit($conn,FALSE);
    $stmt = mysqli_prepare($conn, "INSERT INTO meeting (user_id, title, place, date, topic, lat, lng) VALUES(?,?,?,?,?,?,?)");    //da modificare quando si metteranno le mappe   
    mysqli_stmt_bind_param($stmt,"issssdd", $user_id, $title, $address, $datetime, $topic, $lat, $lng);  
    array_push($res, mysqli_stmt_execute($stmt));

    $meeting_id = mysqli_insert_id($conn);

    if(isset($_POST["user_invited"])){
        $user_invited = $_POST["user_invited"];
       
        
        foreach($user_invited as $i_user_id)  
        {
            $stmt = mysqli_prepare($conn, "INSERT INTO invite (user_id, meeting_id, reply) VALUES(?,?,0)"); 
            mysqli_stmt_bind_param($stmt,"ii", $i_user_id, $meeting_id);  
            array_push($res, mysqli_stmt_execute($stmt));
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn, "SELECT name, surname, email FROM users WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $i_user_id);
            array_push($res,mysqli_stmt_execute($stmt));
            $result = mysqli_stmt_get_result($stmt);            

            if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $name = $row["name"];
                $surname = $row["surname"];
                $email = $row["email"];
                mysqli_stmt_close($stmt);
                $message = "Dear ".$name." ".$surname.", there is a new meeting invite for you!\r\nClick the following link to manage it: http://localhost/homework/meeting_invite_mail_accept.php?meeting=".$meeting_id."&user=".$i_user_id;
                array_push($res, mail($email, 'New Meeting Invite', $message));
            }
        }
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO partecipate (user_id, meeting_id, card_id) VALUES(?,?,?)");    //inserisco la partecipazione del creatore al meeting
    mysqli_stmt_bind_param($stmt,"iii", $user_id, $meeting_id, $card_id);  
    array_push($res, mysqli_stmt_execute($stmt));

    foreach($res as $result){
        if($result == FALSE)
            $res_final = $result;
    }

    if($res_final){
        mysqli_commit($conn);
        echo "Meeting created";
    }
    else{
        mysqli_rollback($conn);
        echo "DB/Mail Error";
    }
    
}else
    echo "POST Error";
?>