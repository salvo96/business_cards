<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$stmt = mysqli_prepare($conn, "SELECT meeting_id
                                FROM meeting
                                WHERE user_id = ".$user_id." AND date =(
                                                SELECT max(date)
                                                FROM meeting
                                                WHERE date <= current_date AND user_id = ?)");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$meeting_id = $row["meeting_id"];
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT count(*) AS partecipants 
                                FROM partecipate
                                WHERE meeting_id = ?");
mysqli_stmt_bind_param($stmt, "i", $meeting_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$data["partecipants"] = $row["partecipants"];
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT count(*) AS no_response
                                FROM invite
                                WHERE meeting_id = ? AND reply = 0");
mysqli_stmt_bind_param($stmt, "i", $meeting_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$data["no_response"] = $row["no_response"];
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT count(*) AS not_partecipants
                                FROM invite
                                WHERE meeting_id = ? AND reply=1 AND user_id not in(SELECT user_id FROM partecipate WHERE meeting_id = ?)");
mysqli_stmt_bind_param($stmt, "ii", $meeting_id, $meeting_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$data["not_partecipants"] = $row["not_partecipants"];
mysqli_stmt_close($stmt);

echo json_encode($data);

?>