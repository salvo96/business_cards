<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);


if(isset($_POST["title"])&&isset($_POST["name"])&&isset($_POST["surname"])&&isset($_POST["email"])&&isset($_POST["phone"])&&isset($_POST["education_id"])&&isset($_POST["work_id"])){
    $title = $_POST["title"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $education_id = $_POST["education_id"];
    $work_id = $_POST["work_id"];    
    
    //quando faccio l'inserimento devo controllare prima che l'id education e work siano appartenenti all'utente
    $stmt = mysqli_prepare($conn, "SELECT user_id FROM education_experience WHERE education_experience_id =?");
    mysqli_stmt_bind_param($stmt, "i", $education_id);
    mysqli_stmt_execute($stmt);    
    mysqli_stmt_bind_result($stmt, $user_id_1);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "SELECT user_id FROM work_experience WHERE work_experience_id =?");
    mysqli_stmt_bind_param($stmt, "i", $work_id);
    mysqli_stmt_execute($stmt);    
    mysqli_stmt_bind_result($stmt, $user_id_2);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    if($user_id_1 == $user_id_2)
        if($user_id == $user_id_1){
            $stmt = mysqli_prepare($conn, "INSERT INTO cards (user_id, title, name, surname, email, phone, education_experience_id, work_experience_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isssssii", $user_id, $title, $name, $surname, $email, $phone, $education_id, $work_id);
            $result = mysqli_stmt_execute($stmt); 
            mysqli_stmt_close($stmt);

            $card_id = mysqli_insert_id($conn);

            if(!empty($_FILES['mypicture']['tmp_name'])){
                $uploadOk = 1;
                $target_file = basename($_FILES["mypicture"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["mypicture"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "File is not an image. ";
                    $uploadOk = 0;
                }
            
                if ($_FILES["mypicture"]["size"] > 20000000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
                    $uploadOk = 0;
                }   
            
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded. ";
                } 
                else {
                    $imagetmp = addslashes(file_get_contents($_FILES['mypicture']['tmp_name']));   
                    $result = mysqli_query($conn, "UPDATE cards SET photo = '".$imagetmp."' WHERE card_id = ".$card_id);
                }
            }
            if($result)
                echo "Card creation completed";
            else
                echo "DB Error";

        }
        else echo "User Experience/Work Experience not correct!";
    else echo "User Experience/Work Experience not correct!";
    
}else
    echo "POST Error";

?>