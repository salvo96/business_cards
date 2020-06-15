<?php
include("database.php");

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

session_start();

if(isset($_POST["login"])&&isset($_POST["password"])&&isset($_POST["cookie"])){
    $login = $_POST["login"];
    $password = $_POST["password"];
    $password = md5($password);
    $cookie = $_POST["cookie"];

    $stmt = mysqli_prepare($conn, "SELECT user_id, passw, activation_date FROM account WHERE login = ?");
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);    
    mysqli_stmt_bind_result($stmt, $user_id, $password_db, $activation_date);
    
    
    if(mysqli_stmt_fetch($stmt)){
        if($password_db == $password){
            mysqli_stmt_close($stmt);
            if($activation_date != NULL){

                if(isset($_POST["fb_login"])){
                    $fb_login = $_POST["fb_login"];

                    $stmt = mysqli_prepare($conn, "UPDATE account SET facebook_id = ? WHERE user_id = ?");
                    mysqli_stmt_bind_param($stmt, "si", $fb_login, $user_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }

                $activation_date = date("d/m/Y", strtotime($activation_date));
                $_SESSION["user_id"] = $user_id;
                $_SESSION["activation_date"] = $activation_date;

                $stmt = mysqli_prepare($conn, "SELECT name, surname FROM users WHERE user_id= ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);       
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
                $_SESSION["user"] = $row["name"]." ".$row["surname"];

                if($cookie == 0){    //voglio unsettare il cookie, quindi l'accesso
                    if(isset($_COOKIE["user_id"])&&isset($_COOKIE["user"])&&isset($_COOKIE["activation_date"])){
                        setcookie("user_id", "", time() - 3600);
                        setcookie("user", "", time() - 3600);
                        setcookie("activation_date", "", time() - 3600);
                    }
                }
                else{ //voglio settare il cookie
                    if(!isset($_COOKIE["user_id"])&&!isset($_COOKIE["user"])&&!isset($_COOKIE["activation_date"])){
                        setcookie("user_id", $user_id, time() + (86400 * 30), "/");
                        setcookie("user", $_SESSION["user"], time() + (86400 * 30), "/");
                        setcookie("activation_date", $activation_date, time() + (86400 * 30), "/");
                    }
                }

                echo 0; //login done
            }
            else
                echo 3; //account not active
        }
        else echo 1; //wrong password
    }
    else echo 2;    //account not found

}
else die("Access Forbidden!");


?>