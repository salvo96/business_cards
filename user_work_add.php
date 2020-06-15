<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["company_id"])&&isset($_POST["role"])&&isset($_POST["year"])&&isset($_POST["place"])){
    $company_id = $_POST["company_id"];
    $role = $_POST["role"];
    $year = $_POST["year"];
    $place = $_POST["place"];

    $stmt = mysqli_prepare($conn, "INSERT INTO work_experience (company_id, user_id, role, year, place) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iisss", $company_id, $user_id, $role, $year, $place);
    
    if(mysqli_stmt_execute($stmt))
        echo "Work Experience Added";
    else
        echo "DB Error";
}else
    echo "POST Error";
?>