<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$stmt = mysqli_prepare($conn, " SELECT meeting_id, organizer, title, partecipants, place
                                FROM(
                                    SELECT M.meeting_id AS meeting_id, M.user_id AS organizer, M.title AS title, M.place AS place, count(*) AS partecipants
                                    FROM meeting M JOIN partecipate P ON M.meeting_id = P.meeting_id 
                                    GROUP BY M.meeting_id
                                )T
                                WHERE meeting_id IN (SELECT meeting_id FROM partecipate WHERE user_id =?)");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = Array();

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    if($row["organizer"] == $user_id){
        $row["organizer"] = 'C';
        $row["control"] = "<i class='fa fa-eye' data-toggle='modal' data-target='#meeting-summary' title='Meeting Overview'></i> <i class='fa fa-pencil-square' data-toggle='modal' data-target='#meeting-update' title='Update Meeting'></i> <i class='fa fa-remove' data-toggle='modal' data-target='#modal-delete' title='Delete Meeting'></i> <i class='fa fa-sign-in' data-toggle='modal' data-target='#meeting-invite' title='Invite partecipants'></i>";
    }
    else{
        $row["organizer"] = 'P';
        $row["control"] = "<i class='fa fa-eye' data-toggle='modal' data-target='#meeting-summary' title='Meeting Overview'></i>  <i class='fa fa-sign-in' data-toggle='modal' data-target='#meeting-invite' title='Invite partecipants'></i> <i class='fa fa-sign-out' data-toggle='modal' data-target='#modal-delete-2' title='Leave Meeting'></i>";
    }

    $row = array_values($row);
    $data[] = $row;
}
mysqli_stmt_close($stmt);

echo '{"data": '.json_encode($data).'}';

?>