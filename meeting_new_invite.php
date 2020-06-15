<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}


$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["user_invited"])&&isset($_POST["meeting_id"])){
    $meeting_id = $_POST["meeting_id"];
    $user_invited = $_POST["user_invited"];
    $error = 0;    
       
    foreach($user_invited as $i_user_id)  
    {
        $stmt = mysqli_prepare($conn, "INSERT INTO invite (user_id, meeting_id, reply) VALUES(?,?,0)"); 
        mysqli_stmt_bind_param($stmt,"ii", $i_user_id, $meeting_id);  
        if(!mysqli_stmt_execute($stmt)){
            $error = 1;
            mysqli_stmt_close($stmt);
        }else{
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($conn, "SELECT name, surname, email FROM users WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $i_user_id);
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);       

                if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $name = $row["name"];
                    $surname = $row["surname"];
                    $email = $row["email"];        
                    mysqli_stmt_close($stmt);
                    $message = "Dear ".$name." ".$surname.", there is a new meeting invite for you!\r\nClick the following link to manage it: http://localhost/homework/meeting_invite_mail_accept.php?meeting=".$meeting_id."&user=".$i_user_id;
                    if(!mail($email, 'New Meeting Invite', $message))
                        echo "Mail Error";
                }
            }
            else{
                $error = 1;
                mysqli_stmt_close($stmt);
            }
        }
    }
    if($error == 0)
        echo "Invites sent correctly";
    else
        echo "DB Error";
}else
    echo "POST Error";


?>