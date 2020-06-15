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
    <?php include("_common_header.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>   
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js"></script>
    <title>Business Cards - Your Profile</title>
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
                Your Profile
                <small>Information about your Education & Work Experience</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li class="active">Your Profile</li>
            </ol>
        </section>

        <section class="content container-fluid">
            <div class='row'>
                <div class="col-md-4">
                    <div class="box box-primary">                        
                        <div class="box-header with-border">
                            <h3 class='box-title'>User Info</h3>
                        </div>
                        <div class="box-body box-profile">

                            <?php 
                                $stmt = mysqli_prepare($conn, "SELECT name, surname FROM users WHERE user_id = ?");
                                mysqli_stmt_bind_param($stmt, "i", $user_id);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);                                
                                if($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
                                    echo '<h3 class="profile-username text-center">'.$row["name"].' '.$row["surname"].'</h3>';
                                mysqli_stmt_close($stmt);
                            ?>                                

                            <div id='profile_picture'><img src='profile_image.php' class='profile-user-img img-responsive img-circle' alt='No picture'></div>
                            <form role='form' id='uploadForm' method="POST" action="upload_profile_image.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="file">Upload your new profile picture</label>
                                    <input type="file" id="myimage" name="myimage">
                                </div>
                                <div class="box-footer">
                                    <input type="submit" class="btn btn-primary" name="submit_image" value="Upload">
                                </div>                                
                            </form>

                        </div>
                    </div>

                </div>

                <div class="col-md-8">
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class="box box-primary"> 
                                <div class="box-header">
                                    <h3 class="box-title">Education and Training</h3> 
                                </div>
                                <div class="box-body">                                    
                                    <button id="add-education" class="btn btn-primary" data-toggle='modal' data-target='#add-education-modal'>Add</button>
                                    <table id="education-training" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Title</th>
                                                <th>Year</th>
                                                <th>Place</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-12'>
                            <div class="box box-primary"> 
                                <div class="box-header">
                                    <h3 class="box-title">Work Experience</h3> 
                                </div>
                                <div class="box-body">            
                                    <button id="add-work" class="btn btn-primary" data-toggle='modal' data-target='#add-work-modal'>Add</button>
                                    <table id="work-experience" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Company</th>
                                                <th>Role</th>
                                                <th>Year</th>
                                                <th>Place</th>
                                                <th>Current Job</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

    <div id="add-education-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Education Info</h4>
            </div>
            <div class="modal-body">
                <p id="message-info"></p> 
                
                <form role="form" id="education-form" name="education-form">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type='text' class="education-field form-control" id='title' name='title' placeholder="Insert Title">
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input id="year" class="education-field form-control" name="year" placeholder="Insert Year" type="number" min="1920" max="<?php echo date("Y"); ?>">
                    </div>
                    <div class="form-group">
                        <label for="place">Place</label>
                        <input id="place" class="education-field form-control" name="place" placeholder="Insert Place" type="text">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="save-education" class="btn btn-primary" type="button" class="btn btn-default">Save</button>
            </div>
            </div>

        </div>
    </div>

    <div id="add-work-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Work Info</h4>
            </div>
            <div class="modal-body">
                <p id="message-info-2"></p>
                <button id="add-company" class="btn btn-primary" class="work-field" data-toggle='modal' data-target='#company-modal'>Add Company</button>
                <form role="form" id="work-form" name="work-form">
                <div class="form-group">
                    <label for="company">Company</label>
                    <select id="company-id" class="work-field form-control"></select>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <input id="role" class="work-field form-control" name="role" placeholder="Insert Role" type="text">
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input id="year-2" class="work-field form-control" name="year" placeholder="Insert Year" type="number" min="1920" max="<?php echo date("Y"); ?>">
                </div>
                <div class="form-group">
                    <label for="place">Place</label>
                    <input id="place-2" class="work-field form-control" name="place" placeholder="Insert Place" type="text">
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="save-work" class="btn btn-primary" type="button" class="btn btn-default">Save</button>
            </div>
            </div>

        </div>
    </div>

    <div id="company-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Company</h4>
            </div>
            <div class="modal-body">
                <p id="message-info-3"></p>
                    <form role="form" id="company-form" name="company-form">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" class="company-field form-control" name="name" placeholder="Insert Name" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Place</label>
                            <input id="place-3" class="company-field form-control" name="place" placeholder="Insert Address" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Website</label>
                            <input id="web" class="company-field form-control" name="web" placeholder="Insert Website" type="text">
                        </div>                        
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input id="email" class="company-field form-control" name="email" placeholder="Insert Email" type="email">
                        </div>
                    </form>
            </div>
            <div class="modal-footer">
                <button id="save-company" class="btn btn-primary" type="button" class="btn btn-default">Save</button>
            </div>
            </div>

        </div>
    </div>
    <!--END WRAPPER-->

        <div class="modal fade" id="modal-delete">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Action</h4>
              </div>
              <div class="modal-body">
                <p>Are you sure to delete this item?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id='confirm-delete' class="btn btn-primary" data-dismiss="modal">Confirm</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <?php include("_message.php"); ?>
            
            </section>
    <!-- /.content -->
        </div>
    </div>

    <script>
        var education_id;
        var work_id;
        var company_id;
        var mode;

        $(document).ready(function() { 
            var table1 = $('#education-training').DataTable( {
                "ajax": "/homework/user_education_info.php",
                "searching": false,
                "lengthChange": false,
                "pageLength": 2
            } );
            table1.column( 0 ).visible( false );
            
            $("#add-education").on('click', function(){
                $(".education-field").val('');
                $("#message-info").text("Insert new data following: ");
                mode = 0;
            });

            $('#save-education').on('click', function (){
                var title = $('#title').val();
                var year = $('#year').val();
                var place = $('#place').val();
                var form = $("#education-form");
                if(form.valid()){
                    $(this).attr("data-dismiss", "modal");
                    if(mode == 0)
                        $.ajax({
                                type: "POST",
                                url: "/homework/user_education_add.php",
                                data: {title: title, year: year, place: place},
                                dataType: "html",
                                success: function(msg)
                                {
                                    $('#education-training').DataTable().ajax.reload(); 
                                    message(msg);                
                                }
                        }); 
                    else
                        $.ajax({
                                type: "POST",
                                url: "/homework/user_education_update.php",
                                data: {title: title, year: year, place: place, education_id: education_id},
                                dataType: "html",
                                success: function(msg)
                                {
                                    $('#education-training').DataTable().ajax.reload();
                                    message(msg);                 
                                }
                        }); 
                }else
                    $(this).attr("data-dismiss", "");
                    

            });

            $('#education-training tbody').on( 'click', '.fa-pencil-square', function () {
                var data = table1.row( $(this).parents('tr') ).data();
                education_id = data[0];
                mode = 1;
                $("#message-info").text("Modify data following: ");
                $.ajax({
                        type: "POST",
                        url: "/homework/education_overview.php",
                        data: {education_id: education_id},
                        dataType: "json",
                        success: function(msg)
                        {
                            var title = msg.title;
                            var year = msg.year;
                            var place = msg.place;
                            $('#title').val(title); 
                            $('#year').val(year); 
                            $('#place').val(place);                      
                        }
                });                      
            } );
            
            $('#education-training tbody').on( 'click', '.fa-remove', function () {
                var data = table1.row( $(this).parents('tr') ).data();
                education_id = data[0];
                mode = 0;   //modalità cancellazione education
                                 
            } );

            $('#confirm-delete').on('click', function(){
                if(mode == 0){
                    $.ajax({
                            type: "POST",
                            url: "/homework/user_education_delete.php",
                            data: {education_id:education_id},
                            dataType: "html",
                            success: function(msg)
                            {
                                $('#education-training').DataTable().ajax.reload();  
                                message(msg);                                             
                            }
                    });
                }
                else{
                    $.ajax({
                            type: "POST",
                            url: "/homework/user_work_delete.php",
                            data: {work_id:work_id},
                            dataType: "html",
                            success: function(msg)
                            {
                                $('#work-experience').DataTable().ajax.reload();  
                                message(msg);                                             
                            }
                    });  
                }
            });

            var table2 = $('#work-experience').DataTable( {
                "ajax": "/homework/user_work_info.php",
                "searching": false,
                "lengthChange": false,
                "pageLength": 2
            } );
            table2.column( 0 ).visible( false );

            $("#add-work").on('click', function(){
                $(".work-field").val('');
                $("#message-info-2").text("Insert new data following: ");
                mode = 0;
                $.ajax({
                    type: "POST",
                    url: "/homework/company_list.php",
                    dataType: "html",
                    success: function(msg)
                    {
                        $("#company-id").html(msg);                    
                    }
                });
            });

            $('#save-work').on('click', function (){
                var role = $('#role').val();
                var year = $('#year-2').val();
                var place = $('#place-2').val();
                company_id = $("#company-id").val();
                var form = $("#work-form");
                if(form.valid()){
                    $(this).attr("data-dismiss", "modal");
                    if(mode == 0)
                        $.ajax({
                                type: "POST",
                                url: "/homework/user_work_add.php",
                                data: {company_id: company_id, role: role, year: year, place: place},
                                dataType: "html",
                                success: function(msg)
                                {
                                    $('#work-experience').DataTable().ajax.reload(); 
                                    message(msg);                
                                }
                        }); 
                    else
                        $.ajax({
                                type: "POST",
                                url: "/homework/user_work_update.php",
                                data: {company_id: company_id, role: role, year: year, place: place, work_id: work_id},
                                dataType: "html",
                                success: function(msg)
                                {
                                    $('#work-experience').DataTable().ajax.reload(); 
                                    message(msg);                
                                }
                        }); 
                }else
                    $(this).attr("data-dismiss", "");
                        

            });

            $('#work-experience tbody').on( 'click', '.fa-pencil-square', function () {   //inserire validazione campi modifica meeting
                var data = table2.row( $(this).parents('tr') ).data();
                work_id = data[0];
                mode = 1;
                $("#message-info-2").text("Modify data following: ");
                $.ajax({
                        type: "POST",
                        url: "/homework/work_overview.php",
                        data: {work_id: work_id},
                        dataType: "json",
                        success: function(msg)
                        {
                            company_id = msg.company_id;
                            var role = msg.role;
                            var year = msg.year;
                            var place = msg.place;
                            $.ajax({
                                type: "POST",
                                url: "/homework/company_list.php",
                                data: {company_id: company_id}, 
                                dataType: "html",
                                success: function(msg)
                                {
                                    $("#company-id").html(msg);                   
                                }
                            });
                            $('#role').val(role); 
                            $('#year-2').val(year); 
                            $('#place-2').val(place);                      
                        }
                });                      
            } );

            $('#work-experience tbody').on( 'click', '.fa-remove', function () {
                var data = table2.row( $(this).parents('tr') ).data();
                work_id = data[0];
                mode = 1;   //modalità cancellazione work experience
                               
            } );

            $('#work-experience tbody').on( 'click', '.fa-plus', function () {
                var data = table2.row( $(this).parents('tr') ).data();
                work_id = data[0];
                $.ajax({
                        type: "POST",
                        url: "/homework/user_work_default.php",
                        data: {work_id:work_id},
                        dataType: "html",
                        success: function(msg)
                        {
                            $('#work-experience').DataTable().ajax.reload(); 
                            message(msg);                                              
                        }
                });                    
            } );

            $('#work-experience tbody').on( 'click', '.fa-check', function () {
                var data = table2.row( $(this).parents('tr') ).data();
                work_id = data[0];
                $.ajax({
                        type: "POST",
                        url: "/homework/user_work_default_remove.php",
                        data: {work_id:work_id},
                        dataType: "html",
                        success: function(msg)
                        {
                            $('#work-experience').DataTable().ajax.reload(); 
                            message(msg);                                              
                        }
                });                    
            } );

            $("#add-company").on('click', function(){
                $(".company-field").val('');
            });

            $('#save-company').on('click', function (){
                var name = $('#name').val();
                var place = $('#place-3').val();
                var web = $('#web').val();
                var email = $('#email').val();
                var form = $("#company-form");
                if(form.valid()){
                    $(this).attr("data-dismiss", "modal");
                    $.ajax({
                        type: "POST",
                        url: "/homework/company_add.php",
                        data: {name: name, place: place, web: web, email: email},
                        dataType: "html",
                        success: function(message){             
                            $.ajax({
                                type: "POST",
                                url: "/homework/company_list.php",
                                data: {company_id: company_id}, 
                                dataType: "html",
                                success: function(msg)
                                {
                                    $("#company-id").html(msg);                   
                                }
                            });
                        }
                    }); 
                }else
                    $(this).attr("data-dismiss", "");
            });

                $("#uploadForm").on('submit',(function(e){
                    e.preventDefault();
                    $.ajax({
                        url: "upload_profile_image.php",
                        type: "POST",
                        data:  new FormData(this),
                        contentType: false,
                        cache: false,
                        processData:false,
                    success: function(data){
                        $("#profile_picture").html("<img src='profile_image.php' class='profile-user-img img-responsive img-circle'>");
                        message(data);
                    }	        
                    });
                }));

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

            $("form[name='education-form']").validate({
                rules:{
                    title: "required",
                    year: "required",
                    place: 'required'
                },
                messages:{
                    title: "Please enter Title",
                    year: "Please enter Year",
                    place: "Please enter Place"
                }
            });

            $("form[name='work-form']").validate({
                rules:{
                    company: "required",
                    role: "required",
                    year: 'required',
                    place: 'required'
                },
                messages:{
                    company: "Please select a Company or create a new one",
                    role: "Please enter Role",
                    year: "Please enter Year",
                    place: "Please enter Place"
                }
            });

            $("form[name='company-form']").validate({
                rules:{
                    name: "required",
                    place: "required",
                    web: 'required',
                    email: 'required'
                },
                messages:{
                    name: "Please enter Name",
                    place: "Please enter Place",
                    web: "Please enter Website",
                    email: "Please enter email"
                }
            });

        });
        

    </script>
    
</body>
</html>