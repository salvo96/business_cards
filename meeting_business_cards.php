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
    
    echo "            
    <div id='myCarousel' class='carousel slide' data-ride='carousel' data-interval='false'>   

        <!-- Wrapper for slides -->
        <div class='carousel-inner'>
        <!--Parte generata dinamicamente-->";  
        
        $stmt = mysqli_prepare($conn, "SELECT C.user_id AS c_user_id, C.card_id AS card_id, C.photo, CONCAT(C.title, ' ',C.name,' ', C.surname) AS name, W.role AS role, E.name AS company, S.title AS education, C.phone AS phone, C.email AS email
        FROM (((cards C JOIN work_experience W ON C.work_experience_id = W.work_experience_id) JOIN company E on W.company_id = E.company_id) JOIN education_experience S ON C.education_experience_id = S.education_experience_id) JOIN partecipate P ON C.card_id = P.card_id
        WHERE P.meeting_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $meeting_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $active = 1;

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            if($active == 1){
                echo "<div class='item active'>";
                $active = 0;
            }
            else
                echo "<div class='item'>";
                echo "<div class='card'>
                            <div class='back'>
                                <img src='card_image.php?card_id=".$row["card_id"]."' class='profile-user-img img-responsive img-circle' alt='No picture'>
                                <h1 class='card-header-1'>".$row["name"]."<span>".$row["role"]."<i>@</i>".$row["company"]."</span></h1>
                                <h2 class='card-header-2'>".$row["education"]."</h2>
                                <ul class='card-list'>
                                    <li class='card-element'>".$row["phone"]."</li>
                                    <li class='card-element'>".$row["email"]."</li>
                                </ul>";
                                if($row["c_user_id"]!=$user_id)
                                    echo "<div id='check'><input class='radioButton' type='radio' data-card_id=".$row["card_id"].">Select this card</div>";
                                else
                                    echo "<div style='color:red; text-align:center; font-size:20px'>Your Business Card for selected meeting </div>";
                        echo "</div>
                            </div>
                        </div>";
        }
        mysqli_stmt_close($stmt);

        if($active == 1){
            echo "<div style='text-align: center; margin-top: 150px; font-size: 30px'>No partecipants for the selected meeting</div>";
        }else
        echo "</div>

        <a class='left carousel-control' href='#myCarousel' data-slide='prev'>
        <span class='glyphicon glyphicon-chevron-left'></span>
        <span class='sr-only'>Previous</span>
        </a>
        <a class='right carousel-control' href='#myCarousel' data-slide='next'>
        <span class='glyphicon glyphicon-chevron-right'></span>
        <span class='sr-only'>Next</span>
        </a>
    </div>";
}
?>