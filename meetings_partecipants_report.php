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
    $data = Array();

    $stmt = mysqli_prepare($conn, "SELECT user_id FROM meeting WHERE meeting_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $meeting_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        mysqli_stmt_close($stmt);
        if($row["user_id"] == $user_id){
            $stmt = mysqli_prepare($conn, "SELECT M.title AS meeting, M.date AS date, M.place AS place, M.topic AS topic, U.name AS name, U.surname AS surname, W.note AS note, W.professionality AS professionality, W.impression AS impression, W.availability AS availability, current_date()>Date AS Past_meeting 
            FROM ((wallet W JOIN cards C on W.card_id = C.card_id) JOIN users U on C.user_id = U.user_id) JOIN meeting M on W.meeting_id = M.meeting_id
            WHERE W.meeting_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $meeting_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $data[] = $row;
            }
            mysqli_stmt_close($stmt);

            echo json_encode($data);

        }else
            echo json_encode("Partecipant Meeting");
    }else{
        echo "DB Error";
        mysqli_stmt_close($stmt);
    }
    
}else
    echo "POST Error";

?>