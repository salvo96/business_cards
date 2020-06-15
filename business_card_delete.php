<?php

include("database.php");

session_start();

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

if(isset($_POST["card_id"])){
    $card_id = $_POST["card_id"];

    //controllo che la card non sia attualmente utilizzata per partecipare ad un meeting: tabella PARTECIPATE (quindi possibilmente anche in WALLET)
    $stmt = mysqli_prepare($conn, "SELECT * FROM partecipate WHERE card_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $card_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        echo "You cannot delete this card: it has been used for a meeting";
        mysqli_stmt_close($stmt);
    }
    else{
        mysqli_stmt_close($stmt);        
        $stmt = mysqli_prepare($conn, "DELETE FROM cards WHERE card_id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $card_id, $user_id);
        if(mysqli_stmt_execute($stmt))
            echo "Card deleted";
        else
            echo "DB Error";
        
        mysqli_stmt_close($stmt);
    }

}else
    echo "POST Error";

?>
