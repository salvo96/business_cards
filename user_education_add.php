<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["title"])&&isset($_POST["year"])&&isset($_POST["place"])){
    $title = $_POST["title"];
    $year = $_POST["year"];
    $place = $_POST["place"];

    $stmt = mysqli_prepare($conn, "INSERT INTO education_experience (title, year, place, user_id) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sisi", $title, $year, $place, $user_id);
    if(mysqli_stmt_execute($stmt))
        echo "Education Experience Added";
    else
        echo "DB Error";
}else
    echo "POST Error";
?>