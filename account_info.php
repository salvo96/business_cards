<?php
include("database.php");

session_start();

if(isset($_COOKIE["user_id"])){
    $_SESSION["user_id"] = $_COOKIE["user_id"];
    $_SESSION["user"] = $_COOKIE["user"];
    $_SESSION["activation_date"] = $_COOKIE["activation_date"];
}

if(!isset($_SESSION["user_id"])){
    die("Access forbidden!");
}

$user_id = $_SESSION["user_id"];

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    
    <title>Business Cards - Account Info</title>
    <?php include("_common_header.php"); ?>
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js"></script>
    
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!--HEADER-->
    <?php include("_header_top.php"); ?>
     <!--           -->
     
     <!--NAVBAR-->
     <?php include("_navigation.php"); ?><!--realizzare script per gestire pagine visitate-->
    <!--        -->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                Account Info
                <small>Your Account information</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li class="active">Account Info</li>
            </ol>
        </section>

        <section class="content container-fluid">
    <!--PAGE CONTENT-->
<div class='row'>

    <div class="col-md-6">

        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Your Account Info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id='account-form' name='account-form'>
              <div class="box-body">

                <div class="form-group">
                  <label for="login">Login</label>
                  <input type='text' class="form-control" id='login' name='login' placeholder="Your Login" readonly>
                </div>

                <div class="form-group">
                  <label for="name">Name</label>
                  <input type='text' class="form-control" id='name' name='name' placeholder="Your Name">
                </div>

                <div class="form-group">
                  <label for="surname">Surname</label>
                  <input type='text' class="form-control" id='surname' name='surname' placeholder="Your Surname">
                </div>

                <div class="form-group">
                  <label for="email">Email address</label>
                  <input type="email" class="form-control" id='email' name='email' placeholder="Your email">
                </div>

                <div class="form-group">
                  <label for="date">Birthdate</label>
                  <input type="date" class="form-control" id='date' name='date' min="1900-01-01" max="<?php echo date("Y-m-j"); ?>">
                </div>
                <div class="form-group">
                    <button type="button" id='change_password' class="btn btn-primary" data-toggle='modal' data-target='#modify_password' >Change password</button>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="button" id='update' value='Update' class="btn btn-primary">
              </div>
            </form>
        </div>
    </div>
</div>

    <div id="modify_password" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form role="form" id='password_form' name='password_form'>

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change your password</h4>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="old_password">Old Password</label>
                                <input type='password' class="form-control" id='old_password' name='old_password' placeholder="Insert Old Password">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type='password' class="form-control" id='new_password' name='new_password' placeholder="Insert New Password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type='password' class="form-control" id='confirm_password' name='confirm_password' placeholder="Confirm Password">
                            </div>

                                
                            
                    </div>
                    <div class="modal-footer">
                        <button id="save_new_password" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include("_message.php"); ?>

    </section>
    <!-- /.content -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "/homework/account_info_overview.php",
                dataType: "json",
                success: function(msg)
                {
                    $("#login").val(msg.login);
                    $('#name').val(msg.name);
                    $('#surname').val(msg.surname);
                    $('#email').val(msg.email);
                    $('#date').val(msg.date);
                    
                }
            });

            $("form[name='account-form']").validate({
                rules:{
                    login: "required",
                    name: "required",
                    surname: 'required',
                    email: 'required',
                    date: 'required'
                },
                messages:{
                    login: "Please enter your login",
                    name: "Please enter your Name",
                    surname: "Please enter your Surname",
                    email: "Please enter your email",
                    date: "Please enter your birthdate"
                }
            });

            $.key('enter', function() {
                var name = $('#name').val();
                var surname = $('#surname').val();
                var email = $('#email').val();
                var date = $('#date').val();
                var form = $("#account-form");
                if(form.valid())
                    $.ajax({
                        type: "POST",
                        url: "/homework/account_info_update.php",
                        data: {name: name, surname: surname, email: email, date:date},
                        dataType: "html",
                        success: function(msg)
                        {
                            message(msg);
                        }
                    });

            });

            $('#update').on('click', function() {
                var name = $('#name').val();
                var surname = $('#surname').val();
                var email = $('#email').val();
                var date = $('#date').val();
                var form = $("#account-form");
                if(form.valid())
                    $.ajax({
                        type: "POST",
                        url: "/homework/account_info_update.php",
                        data: {name: name, surname: surname, email: email, date:date},
                        dataType: "html",
                        success: function(msg)
                        {
                            message(msg);
                        }
                    });
            }); 
            
            /*Script per attivare i link del menu 'sidebar' quando ci si trova all'interno*/
            var url = window.location;

            // for sidebar menu entirely but not cover treeview
            $('ul.sidebar-menu a').filter(function() {
                return this.href == url;
            }).parent().addClass('active');

            // for treeview
            $('ul.treeview-menu a').filter(function() {
                return this.href == url;
            }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
            /*_____________________________________________________________________________*/

            $("form[name='password_form']").validate({
                rules:{
                    old_password: "required",
                    new_password: "required",
                    confirm_password:{
                        equalTo: "#new_password"
                    }
                },
                messages:{
                    old_password: "Please enter your old password",
                    new_password: "Please enter your new password",
                    confirm_password: "Please confirm your new password"
                }
            });

            $('#save_new_password').on('click', function() {
                var old_password = $("#old_password").val();
                var new_password = $("#new_password").val();
                var form = $("#password_form");
                if(form.valid()){
                    $(this).attr("data-dismiss", "modal");
                    $.ajax({
                        type: "POST",
                        url: "/homework/password_update.php",
                        data: {old_password: old_password, new_password: new_password},
                        dataType: "html",
                        success: function(msg)
                        {
                            message(msg);
                        }
                    });
                }
                else
                    $(this).attr("data-dismiss", "");
            });

        });

    </script>


    
</body>
</html>