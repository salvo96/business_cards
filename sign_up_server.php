<?php

include("database.php");

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

session_start();

if(isset($_POST["login"])&&isset($_POST["password"])&&isset($_POST["name"]) //attenzione all'autoincrement delle due tabelle account e user quando inserisco un nuovo utente: prevedere in transaction
    &&isset($_POST["surname"])&&isset($_POST["email"])&&isset($_POST["date"])){ //$result = mysql_query($sql) $last_id = mysql_insert_id(); => ottengo l'id dell'ultimo valore inserito in tabella con QUERY di inserimento
     
        $login = $_POST["login"];
        $password = md5($_POST["password"]);
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $date = $_POST["date"];
        //$date_now = date("Y-m-d");
        //controllo se l'utente con il dato username esiste nel db
        $stmt = mysqli_prepare($conn, "SELECT login FROM account WHERE login = ?");
        mysqli_stmt_bind_param($stmt, "s", $login);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $login_db);
        
        if(mysqli_stmt_fetch($stmt))
            echo 1; //Username già esistente            
        else{
            //si può registrare
            mysqli_autocommit($conn,FALSE);
            
            $stmt = mysqli_prepare($conn, "INSERT INTO Account (login, passw) VALUES(?,?)");       
            mysqli_stmt_bind_param($stmt,"ss", $login, $password);
            $res1 = mysqli_stmt_execute($stmt);
            
            $user_id = mysqli_insert_id($conn);

            $stmt = mysqli_prepare($conn, "INSERT INTO Users (user_id, name, surname, birth, email) VALUES(?,?,?,?,?)");       
            mysqli_stmt_bind_param($stmt,"issss", $user_id, $name, $surname, $date, $email);
            $res2 = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $message = "Dear ".$name." ".$surname.", you have registered a new account!\r\nIf you want to use your new account you need to activate it following: http://localhost/homework/confirm_registration.php?user_id=".$user_id;
            $res3 = mail($email, 'New Account Registration', $message);

            if($res1 && $res2 && $res3){
                mysqli_commit($conn);                
                echo 0; //registrazione completata
                
            }
            else{
                mysqli_rollback($conn);
                echo 2; //Errore nel database
            }
            
        }
    }
    else header("Location: dashboard.php");


?>
