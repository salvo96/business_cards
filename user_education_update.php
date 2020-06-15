<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["title"])&&isset($_POST["year"])&&isset($_POST["place"])&&isset($_POST["education_id"])){
    $title = $_POST["title"];
    $year = $_POST["year"];
    $place = $_POST["place"];
    $education_id = $_POST["education_id"];

    $stmt = mysqli_prepare($conn, "UPDATE education_experience SET title = ?, year = ?, place = ? WHERE education_experience_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "sisii", $title, $year, $place, $education_id, $user_id);
    if(mysqli_stmt_execute($stmt))
        echo "Education Experience Updated"; 
    else
        echo "DB Error";
}else
    echo "POST Error";
?>