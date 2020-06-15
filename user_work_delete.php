<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];


$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["work_id"])){
    $work_id = $_POST["work_id"];

    $stmt = mysqli_prepare($conn, "SELECT user_id FROM work_experience WHERE work_experience_id = ? AND user_id = ?");       
    mysqli_stmt_bind_param($stmt,"ii", $work_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id_c);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if($user_id_c == $user_id){  
        
        $stmt = mysqli_prepare($conn, "SELECT card_id FROM cards WHERE work_experience_id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $work_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, "SELECT current_job FROM users WHERE user_id = ? AND current_job = ?");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $work_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);  
        $row2 = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        
        if($row1 != NULL)     
            echo "You can't delete this work experience: it has been used as a card information";
        else if($row2 != NULL)
            echo "You can't delete this work experience: it has been used as current job";
        else{
            $stmt = mysqli_prepare($conn, "DELETE FROM work_experience WHERE work_experience_id = ?");       
            mysqli_stmt_bind_param($stmt,"i", $work_id);
            if(mysqli_stmt_execute($stmt))
                echo "Work experience deleted";
            else
                echo "DB Error";
            mysqli_stmt_close($stmt);
        }
    }
    else
        echo "Work experience not correct";

}else
    echo "POST Error";

?>