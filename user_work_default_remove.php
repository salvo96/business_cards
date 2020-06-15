<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["work_id"])){
    $work_id = $_POST["work_id"];

    $stmt = mysqli_prepare($conn, "UPDATE users SET current_job = NULL WHERE user_id= ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);  

    if(mysqli_stmt_execute($stmt))
        echo "Current Job Unset";
    else
        echo "DB Error";

    mysqli_stmt_close($stmt);
}else
    echo "POST Error";
?>