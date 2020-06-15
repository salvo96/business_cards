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

    $stmt = mysqli_prepare($conn, "SELECT company_id, role, year, place FROM work_experience WHERE user_id = ? AND work_experience_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $work_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    echo json_encode($row);
}


?>