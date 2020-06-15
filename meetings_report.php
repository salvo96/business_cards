<?php
include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$data = Array();

$stmt = mysqli_prepare($conn, "SELECT M.title AS Meeting_Title, M.place AS Place, M.date AS Date, M.topic AS Topic, C.title AS Title, C.name AS Name, C.surname AS Surname, C.email AS Email, C.phone AS Phone, P.note AS Note, P.useful AS Useful, P.importance AS Importance, current_date()>Date AS Past_meeting
                                FROM (meeting M JOIN partecipate P on M.meeting_id = P.meeting_id) JOIN cards C ON P.card_id = C.card_id 
                                WHERE M.user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $data[] = $row;
}
mysqli_stmt_close($stmt);

echo json_encode($data);

?>