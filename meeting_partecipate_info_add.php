<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["note"])&&isset($_POST["useful"])&&isset($_POST["importance"])&&isset($_POST["meeting_id"])){
    $note = $_POST["note"];
    $useful = $_POST["useful"];
    $importance = $_POST["importance"];
    $meeting_id = $_POST["meeting_id"];

    $stmt = mysqli_prepare($conn, "SELECT current_date() > date FROM meeting WHERE meeting_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $meeting_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $date_passed);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if($date_passed){
        $stmt = mysqli_prepare($conn, "UPDATE partecipate SET note = ?, useful = ?, importance = ? WHERE user_id = ? AND meeting_id = ?");
        mysqli_stmt_bind_param($stmt, "siiii", $note, $useful, $importance, $user_id, $meeting_id);        
        
        if(mysqli_stmt_execute($stmt))
            echo "Meeting evaluation sent";
        else
            echo "DB Error!"; 
        mysqli_stmt_close($stmt); 
    }  
    else
        echo "You can't evaluate a future meeting";
}else
    echo "POST Error";
    

?>