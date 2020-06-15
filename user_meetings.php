<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$stmt = mysqli_prepare($conn, " SELECT organizer, title, partecipants, place, useful, importance  
                                FROM(
                                    SELECT M.meeting_id AS meeting_id, M.user_id AS organizer, M.title AS title, M.place AS place, count(*) AS partecipants, avg(P.useful) AS useful, avg(P.importance) AS importance 
                                    FROM meeting M JOIN partecipate P ON M.meeting_id = P.meeting_id 
                                    GROUP BY M.meeting_id
                                )T
                                WHERE meeting_id IN (SELECT meeting_id FROM partecipate WHERE user_id = ?)");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = Array();

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    if($row["organizer"] == $user_id)
        $row["organizer"] = 'C';
    else
        $row["organizer"] = 'P';
    if(!($row["useful"]==NULL||$row["importance"]==NULL)){
        $avg = round(($row["useful"] + $row["importance"])/2); 
        $star_on = "<a class='br-selected br-current'></a>";
        $star_off = "<a class></a>";
        switch($avg){
            case 1:
                $stars = $star_on.$star_off.$star_off.$star_off.$star_off;
                break;
            case 2:
                $stars = $star_on.$star_on.$star_off.$star_off.$star_off;
                break;
            case 3:
                $stars = $star_on.$star_on.$star_on.$star_off.$star_off;
                break;
            case 4:
                $stars = $star_on.$star_on.$star_on.$star_on.$star_off;
                break;
            case 5:
                $stars = $star_on.$star_on.$star_on.$star_on.$star_on;
                break;
            default:
                $stars = "Evaluation not present";            
        }
        $row["evaluation"] = "<div class='br-wrapper br-theme-fontawesome-stars'><div class='br-widget'>".$stars."</div></div>";
    }
    else
        $row["evaluation"] ="Evaluation not present";
    unset($row["useful"]);
    unset($row["importance"]);
    $row = array_values($row);
    $data[] = $row;
}
mysqli_stmt_close($stmt);

echo '{"data": '.json_encode($data).'}';

?>