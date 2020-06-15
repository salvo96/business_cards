<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

    $uploadOk = 1;
    $target_file = basename($_FILES["myimage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["myimage"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image. ";
        $uploadOk = 0;
    }

    if ($_FILES["myimage"]["size"] > 20000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
    $uploadOk = 0;
    }   

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded. ";
    // if everything is ok, try to upload file
    } 
    else {
        $imagetmp = addslashes(file_get_contents($_FILES['myimage']['tmp_name']));     

        if(mysqli_query($conn, "UPDATE users SET photo = '".$imagetmp."' WHERE user_id =".$user_id))
            echo "Profile image updated";
        else
            echo "DB Error";
    }

?>