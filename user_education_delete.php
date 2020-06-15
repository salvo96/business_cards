<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];


$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["education_id"])){
    $education_id = $_POST["education_id"];

    $stmt = mysqli_prepare($conn, "SELECT user_id FROM education_experience WHERE education_experience_id = ? AND user_id = ?");       
    mysqli_stmt_bind_param($stmt,"ii", $education_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id_c);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if($user_id_c == $user_id){        

        $stmt = mysqli_prepare($conn, "SELECT card_id FROM cards WHERE education_experience_id = ? AND user_id = ?");       
        mysqli_stmt_bind_param($stmt,"ii", $education_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $card_id);
        
        if(mysqli_stmt_fetch($stmt)!=NULL){
            echo "You can't delete this education experience: it has been used as a card information";
            mysqli_stmt_close($stmt);
        }
        else{
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($conn, "DELETE FROM education_experience WHERE education_experience_id = ?");       
            mysqli_stmt_bind_param($stmt,"i", $education_id);
            if(mysqli_stmt_execute($stmt))
                echo "Education experience deleted";
            else
                echo "DB Error";
        }
    }
    else
        echo "Education Experience not correct";

}else
    echo "POST Error";

?>