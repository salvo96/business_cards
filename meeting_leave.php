<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["meeting_id"])){
    $meeting_id = $_POST["meeting_id"];    

    $stmt = mysqli_prepare($conn, "SELECT current_date() < date FROM meeting WHERE meeting_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $meeting_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $date_not_passed);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if($date_not_passed){   //se la data non è ancora passata
        $stmt = mysqli_prepare($conn, "DELETE FROM partecipate WHERE meeting_id = ? AND user_id = ?");    
        mysqli_stmt_bind_param($stmt,"ii", $meeting_id, $user_id);
        if(mysqli_stmt_execute($stmt))
            echo "Meeting left";
        else
            echo "DB Error";   
    }
    else
        echo "You can't leave a past meeting!";
    
}else
    echo "POST Error";

?>