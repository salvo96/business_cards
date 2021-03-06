<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$stmt = mysqli_prepare($conn, "SELECT education_experience_id, title, year, place FROM education_experience WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = Array();
while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
    $row["control"] = "<i class='fa fa-pencil-square' data-toggle='modal' data-target='#add-education-modal' title='Update Info'></i> <i class='fa fa-remove' data-toggle='modal' data-target='#modal-delete' title='Delete Info'></i>";
    $row = array_values($row);
    $data[] = $row;
}

echo '{"data": '.json_encode($data).'}';

?>