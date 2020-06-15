<?php
include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["name"])&&isset($_POST["surname"])&&isset($_POST["email"])&&isset($_POST["date"])){
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $date = $_POST["date"];    
   
    $stmt = mysqli_prepare($conn, "UPDATE Users SET name = ?, surname = ?, birth = ?, email = ? WHERE user_id = ?");       
    mysqli_stmt_bind_param($stmt,"ssssi", $name, $surname, $date, $email, $user_id);
    if(mysqli_stmt_execute($stmt))
        echo "Account Info Modified";
    else
        echo "DB Error";
    
}else
    echo "POST Error";


?>