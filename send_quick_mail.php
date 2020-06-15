<?php
include("database.php");

session_start();


if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["email"])&&isset($_POST["subject"])&&isset($_POST["text"])){
    $email = $_POST["email"];
    $subject = "[Quick Mail from Business Card] ".$_POST["subject"];
    $text = $_POST["text"];

    $stmt = mysqli_prepare($conn, "SELECT CONCAT(name, ' ', surname) AS name
                                    FROM users
                                    WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $EmailFrom= "From:".$row["name"];
        if(mail($email, $subject, $text, $EmailFrom))
            echo "Quick Mail Sent";
        else
            echo "Mail Error";
    }else
        echo "DB Error";    
    mysqli_stmt_close($stmt);
}else
    echo "POST Error";
?>