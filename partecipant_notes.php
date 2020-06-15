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

    $stmt = mysqli_prepare($conn, "SELECT note, professionality, impression, availability FROM wallet WHERE user_id = ? AND meeting_id = ? AND card_id = ?");
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $meeting_id, $card_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);    

    $info = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT U.user_id AS p_user_id, CONCAT(U.name, ' ', U.surname) AS name
    FROM cards C JOIN users U on C.user_id = U.user_id 
    WHERE C.card_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $card_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);    

    $name = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    $row = Array();

    if($info == NULL){
        $row["note"] = '';
        $row["professionality"] = '';
        $row["impression"] = '';
        $row["availability"] = '';
    }
    else
        $row = $info;
    $row["name"] = $name["name"];
    $row["p_user_id"]=$name["p_user_id"];

    echo json_encode($row);
}


?>