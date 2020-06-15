<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["meeting_id"])){
    $meeting_id = $_POST["meeting_id"];

    $stmt = mysqli_prepare($conn, "UPDATE invite SET reply = 1 WHERE user_id = ? AND meeting_id = ?");
    mysqli_stmt_bind_param($stmt, "ii",$user_id, $meeting_id);

    if(mysqli_stmt_execute($stmt))
        echo "Invite declined"; //invite declined
    else
        echo "Database Error"; //errore DB
    
    mysqli_stmt_close($stmt);
}
else
    echo "POST Error";//errore POST
?>