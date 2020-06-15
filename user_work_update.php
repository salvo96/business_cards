<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["company_id"])&&isset($_POST["role"])&&isset($_POST["year"])&&isset($_POST["place"])&&isset($_POST["work_id"])){
    $company_id = $_POST["company_id"];
    $role = $_POST["role"];
    $year = $_POST["year"];
    $place = $_POST["place"];
    $work_id = $_POST["work_id"];

    $stmt = mysqli_prepare($conn, "UPDATE work_experience SET company_id = ?, role = ?, year = ?, place = ? WHERE work_experience_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "isisii", $company_id, $role, $year, $place, $work_id, $user_id);
    
    if(mysqli_stmt_execute($stmt))
        echo "Work Experience Updated"; 
    else
        echo "DB Error";
}else
    echo "POST Error";
?>