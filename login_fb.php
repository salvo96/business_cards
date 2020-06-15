<?php
include("database.php");

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

session_start();

if(isset($_POST["fb_login"])){
    $fb_login = $_POST["fb_login"];
    
    $stmt = mysqli_prepare($conn, "SELECT A.user_id AS user_id, A.activation_date AS activation_date, U.name AS name, U.surname AS surname FROM account A JOIN users U on A.user_id = U.user_id WHERE facebook_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $fb_login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        //puoi fare il login
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["activation_date"] = $row["activation_date"];
        $_SESSION["user"] = $row["name"]." ".$row["surname"];
        echo 0; //login done
    }
    else
        echo 1;//non c'è

    mysqli_stmt_close($stmt);
}else
    echo 2;


?>