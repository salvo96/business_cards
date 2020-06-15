<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$stmt = mysqli_prepare($conn, "SELECT current_job FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$current_job = $row["current_job"];
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT W.work_experience_id AS work_id, C.name AS company, W.role AS role, W.year AS year, W.place AS place  FROM work_experience W JOIN company C ON W.company_id = C.company_id WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = Array();
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    if($row["work_id"] == $current_job)
        $row["current_job"] = "<i class='fa fa-check' title='Unset Current Job'></i>";
    else
        $row["current_job"] = "<i class='fa fa-plus' title='Set Current Job'></i>";
    $row["control"] = "<i class='fa fa-pencil-square' data-toggle='modal' data-target='#add-work-modal' title='Update Info'></i> <i class='fa fa-remove' data-toggle='modal' data-target='#modal-delete' title='Delete Info'></i>";
    $row = array_values($row);
    $data[] = $row;
}
mysqli_stmt_close($stmt);

echo '{"data": '.json_encode($data).'}';

?>