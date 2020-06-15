<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$stmt = mysqli_prepare($conn, "SELECT month(date) AS month, count(*) AS meetings
                                FROM meeting 
                                WHERE year(date) = year(DATE_SUB(curdate(), INTERVAL 1 YEAR)) AND user_id = ?
                                group by month(date)");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = Array();

for($i=0;$i<12;$i++)
    $data[$i]=0;

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $data[$row["month"]-1] = $row["meetings"];
}
mysqli_stmt_close($stmt);

echo json_encode($data);

?>