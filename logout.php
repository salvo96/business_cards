<?php

session_start();

if(isset($_SESSION["user_id"])){
    setcookie("user_id", "", time() - 3600, '/');
    setcookie("user", "", time() - 3600, '/');
    setcookie("activation_date", "", time() - 3600, '/');
    session_unset();
    session_destroy();
    header("Location: index.php");
}

?>