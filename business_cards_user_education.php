<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["card_id"])){
    $card_id = $_POST["card_id"];

    $stmt = mysqli_prepare($conn, "SELECT education_experience_id FROM cards WHERE card_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $card_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $education_id = $row["education_experience_id"];

    $stmt = mysqli_prepare($conn, "SELECT education_experience_id, title, year, place FROM education_experience WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = Array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        if($row["education_experience_id"]==$education_id)
            $row["check"]="<input class='radioButton' type='radio' checked>";
        else
            $row["check"]="<input class='radioButton' type='radio'>";
        $row = array_values($row);
        $data[] = $row;
    }

    echo '{"data": '.json_encode($data).'}';
}

?>