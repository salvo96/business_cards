<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["note"])&&isset($_POST["professionality"])&&isset($_POST["impression"])&&isset($_POST["availability"])&&isset($_POST["card_id"])&&isset($_POST["meeting_id"])){
    $note = $_POST["note"];
    $professionality = $_POST["professionality"];
    $impression = $_POST["impression"];
    $availability = $_POST["availability"];
    $card_id = $_POST["card_id"];
    $meeting_id = $_POST["meeting_id"];

    $stmt = mysqli_prepare($conn, "SELECT user_id
                                    FROM cards
                                    WHERE card_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $card_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    if($row["user_id"] != $user_id){        

        $stmt = mysqli_prepare($conn, "SELECT current_date() > date FROM meeting WHERE meeting_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $meeting_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $date_passed);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if($date_passed){

            $stmt = mysqli_prepare($conn, "SELECT * FROM wallet WHERE user_id = ? AND meeting_id = ? AND card_id = ?");
            mysqli_stmt_bind_param($stmt, "iii", $user_id, $meeting_id, $card_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);                

            if(mysqli_fetch_array($result, MYSQLI_ASSOC) == NULL){  //le note di quel particolare utente per quel particolare meeting non esistono: le devo INSERT nel db
                mysqli_stmt_close($stmt);
                $stmt = mysqli_prepare($conn, "INSERT INTO wallet VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iiisiii", $user_id, $meeting_id, $card_id, $note, $professionality, $impression, $availability);      
                
                if(mysqli_stmt_execute($stmt))
                    echo "Partecipant evaluation sent";
                else
                    echo "DB Error";
                mysqli_stmt_close($stmt);
            }
            else{   //le note esistono: faccio semplice UPDATE
                mysqli_stmt_close($stmt);
                $stmt = mysqli_prepare($conn, "UPDATE wallet SET note = ?, professionality = ?, impression = ?, availability = ? WHERE user_id = ? AND meeting_id = ? AND card_id = ?");
                mysqli_stmt_bind_param($stmt, "siiiiii", $note, $professionality, $impression, $availability, $user_id, $meeting_id, $card_id);
                
                if(mysqli_stmt_execute($stmt))
                    echo "Partecipant evaluation sent";
                else
                    echo "DB Error";
                mysqli_stmt_close($stmt);
            }
        }
        else
            echo "You can't evaluate a future meeting";
    }
    else
        echo "You can't evaluate yourself";
}else
    echo "POST Error";
    

?>