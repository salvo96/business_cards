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
<title>Business Cards - Sign In</title> 
<script type='text/javascript' charset='utf8' src='https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js'></script>
<script type='text/javascript' charset='utf8' src='https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js'></script>   
<script src='../homework/icheck.min.js'></script>
<link rel="stylesheet" href="../homework/iCheck/square/blue.css">
</head>

<body class="hold-transition login-page">



    <div class="login-box">
        <div class="login-logo">
        <a href="#"><b>Business Card</b><br>Social for your Network</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form id='login_form' name='login_form'>
                <div class="form-group has-feedback">
                    <input type='text' class="form-control" id='login' name='login' placeholder='Login'>
                    <span class="glyphicon glyphicon-user form-control-feedback">
                </div>
                <div class="form-group has-feedback">
                    <input type='password' class="form-control" id='password' name='password' placeholder='Password'>
                    <span class="glyphicon glyphicon-lock form-control-feedback">
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                        <input type="checkbox" id='cookie_check'> Remember Me
                        </label>
                    </div>
                    </div>
                    <div class="col-xs-4">
                        <input type='button' class="btn btn-primary btn-block btn-flat" id='sign-in' value='Sign In'>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center">
            <p>- OR -</p>
            <div class="fb-login-button" style="margin-bottom:15px;" onlogin="checkLoginState();" data-max-rows="1" data-size="medium" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false"></div>
            <input type='button' class="btn btn-primary btn-block btn-flat" id='sign-up' value='Create a new account'>
        </div>
    </div>

    <?php include("_message.php"); ?>

    <script>
    var meeting_id = "<?php if(isset($_GET["meeting"])) 
                                echo $_GET["meeting"]; ?>";
    var user = "<?php if(isset($_GET["user"])) 
                        echo $_GET["user"]; ?>";

    var fb_login;
    var cookie = 0;
    $(document).ready(function() {
        $("form[name='login_form']").validate({
            rules:{
                login: "required",
                password: "required",
            },
            messages:{
                login: "Please enter your login",
                password: "Please enter your password",
            }
        });

        $.key('enter', function() {
            var login = $("#login").val();
            var password = $("#password").val();
            var form = $("#login_form");            
            if(form.valid())
                $.ajax({
                    type: "POST",
                    url: "/homework/login.php",
                    data: {login:login, password:password, fb_login:fb_login, cookie:cookie},
                    dataType: "html",
                    success: function(msg)
                    {
                        var response = msg;
                        switch (response){
                            case '0':
                                if(meeting_id != "" && user != "")
                                    $(window.location).attr('href', 'meeting_invite_mail_accept.php?meeting='+meeting_id+'&user='+user);
                                else{
                                    if(fb_login != undefined)
                                        $(window.location).attr('href', 'dashboard.php?facebook_assoc=1');
                                    else
                                        $(window.location).attr('href', 'dashboard.php');
                                }
                                break;
                            case '1':
                                message("Wrong password!");
                                break;
                            case '2':
                                message("Account not found!");
                                break;
                            case '3':
                                message("Account not active! Please check your mail inbox");
                                break;
                            default:
                                message("Server Error!");
                        }
                    }
                });
            
        });

        $('#sign-in').on('click', function() {
            var login = $("#login").val();
            var password = $("#password").val();
            var form = $("#login_form");            
            if(form.valid())
                $.ajax({
                    type: "POST",
                    url: "/homework/login.php",
                    data: {login:login, password:password, fb_login:fb_login, cookie:cookie},
                    dataType: "html",
                    success: function(msg)
                    {
                        var response = msg;
                        switch (response){
                            case '0':
                                if(meeting_id != "" && user != "")
                                    $(window.location).attr('href', 'meeting_invite_mail_accept.php?meeting='+meeting_id+'&user='+user);
                                else{
                                    if(fb_login != undefined)
                                        $(window.location).attr('href', 'dashboard.php?facebook_assoc=1');
                                    else
                                        $(window.location).attr('href', 'dashboard.php');
                                }
                                break;
                            case '1':
                                message("Wrong password!");
                                break;
                            case '2':
                                message("Account not found!");
                                break;
                            case '3':
                                message("Account not active! Please check your mail inbox");
                                break;
                            default:
                                message("Server Error!");
                        }
                    }
                });
        });

        $('#sign-up').on('click', function() {
            $(window.location).attr('href', 'sign_up.php');
        });

        $('input').on('ifChecked', function(event){
            cookie = 1;
        });

        $('input').on('ifUnchecked', function(event){
            cookie = 0;
        });    
       

    });

    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
        });
    });
  
    /*****************************FACEBOOK LOGIN****************************************** */
  function statusChangeCallback(response) {  
    if (response.status === 'connected') {  //mi sono loggato con facebook
        fb_login = response.authResponse.userID;
        $.ajax({
                type: "POST",
                url: "/homework/login_fb.php",
                data: {fb_login:fb_login},
                dataType: "html",
                success: function(msg)
                {
                    switch (msg){
                        case '0':
                            if(meeting_id != "" && user != "")
                                $(window.location).attr('href', 'meeting_invite_mail_accept.php?meeting='+meeting_id+'&user='+user);
                            else
                                $(window.location).attr('href', 'dashboard.php');
                            break;
                        case '1':
                            message("Facebook Login not present. Please sign in to associate Facebook Account");
                            break;
                        case '2':
                            message("POST Error");
                            break;
                        default:
                            message("Server Error!");
                    }
                }
            });
    } 
  }

  function checkLoginState() {
    FB.getLoginStatus(function(response) {  //questo al login (pressione del pulsante)
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1087974114683705',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.8'
    });
  };

  
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  /****************************************************** */
  
  
    </script>
    
</body>
</html>