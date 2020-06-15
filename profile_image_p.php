<?php

header("content-type:image/jpeg");

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}


$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_GET["p_user_id"])){
    $p_user_id = $_GET["p_user_id"];

    $stmt = mysqli_prepare($conn, "SELECT photo FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $p_user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);    

    if($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
        echo $row["photo"];
    }
    mysqli_stmt_close($stmt);
}

?>