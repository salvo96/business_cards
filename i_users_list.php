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

    $stmt = mysqli_prepare($conn, "SELECT u.user_id AS user_id, CONCAT(U.name,' ', U.surname ) AS name, C.name AS company, W.role AS role 
    FROM users U JOIN	(
                            work_experience W JOIN company C ON W.company_id = C.company_id
                        ) ON W.work_experience_id = U.current_job 
    WHERE u.user_id <> ? AND u.user_id NOT IN(SELECT user_id FROM invite WHERE meeting_id = ?) AND u.user_id NOT IN(SELECT user_id FROM meeting WHERE meeting_id = ?)");
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $meeting_id, $meeting_id);
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