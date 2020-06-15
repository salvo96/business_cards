<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

$result = mysqli_query($conn, "SELECT company_id, name FROM company");

if(isset($_POST["company_id"])){
    $company_id_retr = $_POST["company_id"];
}
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $company_id = $row["company_id"];
    $name = $row["name"];
    echo "<option value='".$company_id."' ";

    if(isset($_POST["company_id"])){
        $company_id_retr = $_POST["company_id"];
        if($company_id_retr == $company_id)
            echo "selected='selected'";
    }        
   
    echo ">".$name."</option>";
}
    

    

?>