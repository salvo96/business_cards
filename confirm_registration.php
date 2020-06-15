<?php

include("database.php");

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);


if(isset($_GET["user_id"])){
    $user_id = $_GET["user_id"];
    $date_now = date("Y-m-d");

    $stmt = mysqli_prepare($conn, "SELECT A.activation_date AS activation_date, U.name AS name, U.surname AS surname FROM account A JOIN users U on A.user_id = U.user_id WHERE A.user_id= ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);    
    mysqli_stmt_bind_result($stmt, $activation_date, $name, $surname);

    if(mysqli_stmt_fetch($stmt)){
        if($activation_date == NULL){
            mysqli_stmt_close($stmt);
            session_start();
            $stmt = mysqli_prepare($conn, "UPDATE Account SET activation_date = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "si", $date_now, $user_id);
            mysqli_stmt_execute($stmt);  
            $_SESSION["user_id"] = $user_id;
            $_SESSION["activation_date"] = date("d/m/Y");
            $_SESSION["user"] = $name." ".$surname;
            header("Location: dashboard.php");

        }else
            die("Account already active!");

    }else
        die("Account not present!");

}else
    die("Access forbidden!");


?>