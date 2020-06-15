<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["meeting_id"])){
    $meeting_id = $_POST["meeting_id"];

    $stmt = mysqli_prepare($conn, "SELECT CONCAT(U.name, ' ',U.surname) as name, C.name as company, W.role as role, 'YES' as partecipation   
    FROM ((partecipate P JOIN users U on P.user_id = U.user_id) JOIN work_experience W on U.current_job = W.work_experience_id) JOIN company C on W.company_id = C.company_id
    WHERE P.meeting_id = ?
    UNION
    SELECT CONCAT(U.name, ' ',U.surname) as name, C.name as company, W.role as role, 'NO' as partecipation   
    FROM ((invite I JOIN users U on I.user_id = U.user_id) JOIN work_experience W on U.current_job = W.work_experience_id) JOIN company C on W.company_id = C.company_id
    WHERE I.meeting_id = ? AND I.reply = 1 AND I.user_id not in (SELECT user_id FROM partecipate WHERE meeting_id = ?)");
    mysqli_stmt_bind_param($stmt, "iii", $meeting_id, $meeting_id, $meeting_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);   

    $data = Array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $row = array_values($row);
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);

    echo '{"data": '.json_encode($data).'}';
}

?>