<?php
include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["old_password"])&&isset($_POST["new_password"])){
    $old_password = md5($_POST["old_password"]);
    $new_password = md5($_POST["new_password"]);

    $stmt = mysqli_prepare($conn, "SELECT passw FROM account WHERE user_id= ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);        
        $password_db = $row["passw"];
        mysqli_stmt_close($stmt);
        if($old_password == $password_db){
            $stmt = mysqli_prepare($conn, "UPDATE account SET passw = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "si", $new_password, $user_id);        
            
            if(mysqli_stmt_execute($stmt))
                echo "Password modified!";
            else
                echo "DB Error";
            mysqli_stmt_close($stmt);
        }
        else
            echo "Old password not correct!";
    }
    else{
        echo "DB Error";  
        mysqli_stmt_close($stmt);   
    }
}else
    echo "POST Error";


?>