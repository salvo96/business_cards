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

    $stmt = mysqli_prepare($conn, "SELECT user_id FROM meeting WHERE meeting_id = ? AND user_id = ?");       
    mysqli_stmt_bind_param($stmt,"ii", $meeting_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id_c);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if($user_id_c == $user_id){  

        mysqli_autocommit($conn,FALSE);
        
        $stmt = mysqli_prepare($conn, "DELETE FROM partecipate WHERE meeting_id = ?");       
        mysqli_stmt_bind_param($stmt,"i", $meeting_id);
        $res1 = mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($conn, "DELETE FROM invite WHERE meeting_id = ?");       
        mysqli_stmt_bind_param($stmt,"i", $meeting_id);
        $res2 = mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($conn, "DELETE FROM wallet WHERE meeting_id = ?");       
        mysqli_stmt_bind_param($stmt,"i", $meeting_id);
        $res3 = mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($conn, "DELETE FROM meeting WHERE meeting_id = ? and user_id = ?");       
        mysqli_stmt_bind_param($stmt,"ii", $meeting_id, $user_id);
        $res4 = mysqli_stmt_execute($stmt);

        if($res1&&$res2&&$res3&&$res4){
            mysqli_commit($conn);
            echo "Meeting deleted";
        }
        else{
            mysqli_rollback($conn);
            echo "DB Error";
        }
    }
    else
        echo "Selected meeting not correct";

}else
    echo "POST Error";

?>