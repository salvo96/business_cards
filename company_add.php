<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["name"])&&isset($_POST["place"])&&isset($_POST["web"])&&isset($_POST["email"])){
    $name = $_POST["name"];
    $place = $_POST["place"];
    $web = $_POST["web"];
    $email = $_POST["email"];

    $stmt = mysqli_prepare($conn, "INSERT INTO company (name, place, web, email) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $place, $web, $email);
    if(mysqli_stmt_execute($stmt))
        echo "Company Added";
    else
        echo "DB Error";
}else
    echo "POST Error";
?>