<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["meeting_id"])&&isset($_POST["card_id"])){
    $meeting_id = $_POST["meeting_id"];    
    $card_id = $_POST["card_id"];
    
    mysqli_autocommit($conn,FALSE);

    $stmt = mysqli_prepare($conn, "UPDATE invite SET reply = 1 WHERE user_id= ? AND meeting_id= ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $meeting_id);
    $res1 = mysqli_stmt_execute($stmt);    
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "INSERT INTO partecipate (user_id, meeting_id, card_id) VALUES(?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $meeting_id, $card_id);
    $res2 = mysqli_stmt_execute($stmt);    
    mysqli_stmt_close($stmt);
        
    if($res1&&$res2){
        mysqli_commit($conn);
        echo "Invite accepted";
    }
    else{
        mysqli_rollback($conn);
        echo "Database Error";
    }
    
}else echo "POST Error";
?>