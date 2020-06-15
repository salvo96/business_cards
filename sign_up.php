<?php

session_start();

if(isset($_COOKIE["user_id"])){
    $_SESSION["user_id"] = $_COOKIE["user_id"];
    $_SESSION["user"] = $_COOKIE["user"];
    $_SESSION["activation_date"] = $_COOKIE["activation_date"];
}

if(isset($_SESSION["user_id"])){
    header("Location: dashboard.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php include("_common_header.php"); ?>
    <title>Business Cards - Sign Up</title>
    <script type='text/javascript' charset='utf8' src='https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js'></script>
    <script type='text/javascript' charset='utf8' src='https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js'></script>
    
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="#"><b>Business Card</b><br>Social for your Network</a>
    </div>
    <div class="register-box-body">
    <p class="login-box-msg">Register a new account</p>
    <form id='signup-form' name='signup-form'>
        <div class="form-group has-feedback">
        <input type='text' class="form-control" id='login' name='login' placeholder="Login">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
        <input type="password" class="form-control" id='password' name='password' placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
        <input type="password" class="form-control" id='password_check' name='password_check' placeholder="Confirm Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
        <input type='text' class="form-control" id='name' name='name' placeholder="Name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
        <input type='text' class="form-control" id='surname' name='surname' placeholder="Surname">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
        <input type="email" class="form-control" id='email' name='email' placeholder="E-mail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
        <label for="date">Birthdate</label>
        <input type="date" class="form-control" id='date' name='date' min="1900-01-01" max="<?php echo date("Y-m-j"); ?>">
        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <input type="button" class="btn btn-primary btn-block btn-flat" id='save' value='Register'>
            </div>
        </div>
    </form>
    <a href="index.php" class="text-center">I already have an account</a>
    </div>
</div>

<?php include("_message.php"); ?>

    <script>
        $(document).ready(function() {
            $("form[name='signup-form']").validate({
                rules:{
                    login: "required",
                    password: { 
                        required: true,
                        minlength: 6
                    },
                    password_check:{
                        equalTo: "#password"
                    },
                    name: "required",
                    surname: 'required',
                    email: 'required',
                    date: 'required'
                },
                messages:{
                    login: "Please enter your login",
                    password: "Please enter your password: min 6 characters",
                    password_check: "Please confirm your password",
                    name: "Please enter your Name",
                    surname: "Please enter your Surname",
                    email: "Please enter your email",
                    date: "Please enter your birthdate"
                }
            });

            $.key('enter', function() {
                var login = $("#login").val();
                var password = $("#password").val();
                var name = $('#name').val();
                var surname = $('#surname').val();
                var email = $('#email').val();
                var date = $('#date').val();
                var form = $("#signup-form");
                if(form.valid())
                    $.ajax({
                        type: "POST",
                        url: "/homework/sign_up_server.php",
                        data: {login:login, password:password, name: name, surname: surname, email: email, date: date},
                        dataType: "html",
                        success: function(msg)
                        {
                            var response = msg;
                            switch (response){
                                case '0':
                                    message("Signup process completed! Activate your account using the link sent to "+email);                
                                    setInterval(function(){
                                        $(window.location).attr('href', 'index.php');
                                    },2000);                                      
                                    break;
                                case '1':
                                    message("Username already registered");
                                    break;
                                default:
                                    message("Server Error!");
                            }
                        }
                    });

            });

            $('#save').on('click', function() {
                var login = $("#login").val();
                var password = $("#password").val();
                var name = $('#name').val();
                var surname = $('#surname').val();
                var email = $('#email').val();
                var date = $('#date').val();
                var form = $("#signup-form");
                if(form.valid())
                    $.ajax({
                        type: "POST",
                        url: "/homework/sign_up_server.php",
                        data: {login:login, password:password, name: name, surname: surname, email: email, date: date},
                        dataType: "html",
                        success: function(msg)
                        {
                            var response = msg;
                            switch (response){
                                case '0':
                                    message("Signup process completed! Activate your account using the link sent to "+email);                
                                    setInterval(function(){
                                        $(window.location).attr('href', 'index.php');
                                    },2000);                                      
                                    break;
                                case '1':
                                    message("Username already registered");
                                    break;
                                default:
                                    message("Server Error!");
                            }
                        }
                    });
            });       

    });

    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
        });
    });

    </script>
    
</body>
</html>