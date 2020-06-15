<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["meeting_id"])&&isset($_POST["title"])&&isset($_POST["place"])&&isset($_POST["date"])&&isset($_POST["topic"])&&isset($_POST["lat"])&&isset($_POST["lng"])){
    $meeting_id = $_POST["meeting_id"];
    $title = $_POST["title"];
    $place = $_POST["place"];
    $date = $_POST["date"];
    $topic = $_POST["topic"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];

    $stmt = mysqli_prepare($conn, "UPDATE meeting SET title = ?, place = ?, date = ?, topic = ?, lat = ?, lng = ? WHERE meeting_id = ? AND user_id = ?");       
    mysqli_stmt_bind_param($stmt,"ssssddii", $title, $place, $date, $topic, $lat, $lng, $meeting_id, $user_id);
    if(mysqli_stmt_execute($stmt))
        echo "Meeting Information Updated";
    else
        echo "DB Error";

}else
    echo "POST Error";

?>