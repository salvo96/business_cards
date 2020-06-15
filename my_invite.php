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

    $stmt = mysqli_prepare($conn, "SELECT I.meeting_id AS meeting_id, CONCAT(U.name, ' ', U.surname) AS organizer, M.title AS title, M.place AS place, date(M.date) AS date, time(M.date) AS hour  
    FROM (invite I JOIN meeting M on I.meeting_id = M.meeting_id) JOIN users U on M.user_id = U.user_id
    WHERE I.reply = 0 AND I.meeting_id = ? AND I.user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $meeting_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = Array();
    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $date = date_create_from_format('Y-m-d', $row["date"]);
        $row["date"] = date_format($date, 'd/m/Y');

        $date = date_create_from_format('H:i:s', $row["hour"]);
        $row["hour"] = date_format($date, 'H:i');

        $row = array_values($row);
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);

        echo '{"data": '.json_encode($data).'}';
}


?>