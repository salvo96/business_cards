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
    <title>Business Cards - Update Business Card</title>
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
                Update Business Card
                <small>Modify your business card information</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li><a href="#">Business Cards</a></li>
                <li><a href="business_cards.php">My Cards</a></li>
                <li class="active">Update Business Card</li>
            </ol>
        </section>
        
        <section class="content container-fluid">
        <div class='row'>
                <div class="col-md-4">
                    <div class="box box-primary">                        
                        <div class="box-header with-border">
                            <h3 class='box-title'>User Info</h3>
                        </div>                        
                            <div class="box-body">
                            <form role='form' id='uploadForm' name='uploadForm' method="POST" action="business_card_update.php" enctype="multipart/form-data">   

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input id="title" class="form-control" id='title' name="title" type="text" placeholder="Insert Title">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" class="form-control" id='name' name="name" type="text" placeholder="Insert Name">
                                </div>
                                <div class="form-group">
                                    <label for="surname">Surname</label>
                                    <input id="surname" class="form-control" id='surname' name="surname" type="text" placeholder="Insert Surname">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" class="form-control" id='email' name="email" type="email" placeholder="Insert Email">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input id="phone" class="form-control" id='phone' name="phone" type="text" placeholder="Insert Phone">
                                </div>
                                <div class="form-group">
                                    <label for="file">Upload picture</label>
                                    <input type="file" id="mypicture" name="mypicture">
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
                                    <table id="education-table" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Title</th>
                                                <th>Year</th>
                                                <th>Place</th>
                                                <th></th>
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
                                    <table id="work-table" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Role</th>
                                                <th>Year</th>
                                                <th>Place</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
            </div> 
            <div class='row'>
                <div class='col-md-11'></div>
                <div class='col-md-1'>
                    <input type="submit" name="save-card" id="save-card" value='Save' class="btn btn-primary"> 
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
                
                    <form role="form" id='education-form' name='education-form'>
                        <div class="form-group">
                            <label for="title_m">Title</label>
                            <input type='text' class="education-field form-control" id='title_m' name='title' placeholder="Insert Title">
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
                <button id="save-education" type="button" class="btn btn-primary">Save</button>
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
                <form role="form" id='work-form' name='work-form'>
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
                <button id="save-work" type="button" class="btn btn-primary">Save</button>
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

                    <form role="form" id='company-form' name='company-form'>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name-2" class="company-field form-control" name="name" placeholder="Insert Name" type="text">
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
                            <input id="email-2" class="company-field form-control" name="email" placeholder="Insert Email" type="email">
                        </div>
                    </form>
            </div>
            <div class="modal-footer">
                <button id="save-company" type="button" class="btn btn-primary">Save</button>
            </div>
            </div>

        </div>
    </div>
<!--END WRAPPER-->
<?php include("_message.php"); ?>
            
</section>
    <!-- /.content -->
        </div>
    </div>

    <script>
    var education_id;
    var work_id;
    var company_id;
    

    $(document).ready(function() {
        var card_id = "<?php echo $_POST["card_id"] ?>";

        $.ajax({
            type: "POST",
            url: "/homework/business_cards_user_overview.php",
            data: {card_id:card_id},
            dataType: "json",
            success: function(msg)
            {
                var title = msg.title;
                var name = msg.name;
                var surname = msg.surname;
                var email = msg.email;
                var phone = msg.phone;
                education_id = msg.education_experience_id;
                work_id = msg.work_experience_id;
                $('#card-picture').html("<img src='card_image.php?card_id="+card_id+"' class='profile-user-img img-responsive img-circle' alt='No picture'>");
                $('#title').val(title); 
                $('#name').val(name); 
                $('#surname').val(surname);
                $('#email').val(email);
                $('#phone').val(phone);     
                                                                   
            }
        });

        var table = $('#education-table').DataTable( {
            "ajax": {
                "type": "POST",
                "url": "/homework/business_cards_user_education.php",                
                "data":{
                    "card_id": card_id
                } 
            },
            "searching": false,
            "lengthChange": false,
            "pageLength": 2
        } );
        table.column(0).visible(false);

        $('#education-table').on('click', 'input', function() {
            $(".radioButton").prop('checked', false);
            $(this).prop('checked', true);
            var data = table.row( $(this).parents('tr') ).data();
            education_id = data[0];
        });

        var table2 = $('#work-table').DataTable( {
            "ajax": {
                "type": "POST",
                "url": "/homework/business_cards_user_work.php",                
                "data":{
                    "card_id": card_id
                } 
            },            
            "searching": false,
            "lengthChange": false,
            "pageLength": 2
        } );
        table2.column( 0 ).visible( false );

        $('#work-table').on('click', 'input', function() {
            $(".radioButton2").prop('checked', false);
            $(this).prop('checked', true);
            var data = table2.row( $(this).parents('tr') ).data();
            work_id = data[0];
        });

        $("#save-card").on('click',(function(e){
            var formData = new FormData(document.getElementById("uploadForm"));
            formData.append("education_id", education_id);
            formData.append("work_id", work_id);
            formData.append("card_id", card_id);
            e.preventDefault(); 
            var form = $("#uploadForm");          
                if(form.valid())        
                        $.ajax({
                            url: "business_card_update.php",
                            type: "POST",
                            data:  formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(data){//inserire eventuali controlli per errori con feedback
                                message(data);
                                setInterval(function(){
                                    $(window.location).attr('href', 'business_cards.php');
                                },2000);
                            }	        
                        });
                    else
                        message("Select Education and/or Work Experience!");
        }));

        $("#add-education").on('click', function(){
            $(".education-field").val('');
            $("#message-info").text("Insert new data following: ");
        });

        $('#save-education').on('click', function (){
            var title = $('#title_m').val();
            var year = $('#year').val();
            var place = $('#place').val();
            var form = $("#education-form");
            if(form.valid()){
                $(this).attr("data-dismiss", "modal");
                 $.ajax({
                    type: "POST",
                    url: "/homework/user_education_add.php",
                    data: {title: title, year: year, place: place},
                    dataType: "html",
                    success: function(msg)
                    {
                        $('#education-table').DataTable().ajax.reload();   
                        message(msg);              
                    }
                });  
            }else
                $(this).attr("data-dismiss", "");              

        });

        $("#add-work").on('click', function(){
            $(".work-field").val('');
            $("#message-info-2").text("Insert new data following: ");
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
                    $.ajax({
                        type: "POST",
                        url: "/homework/user_work_add.php",
                        data: {company_id: company_id, role: role, year: year, place: place},
                        dataType: "html",
                        success: function(msg)
                        {
                            $('#work-table').DataTable().ajax.reload();   
                            message(msg);              
                        }
                    }); 
                }else
                    $(this).attr("data-dismiss", "");                

            });

            $("#add-company").on('click', function(){
                $(".company-field").val('');
            });

            $('#save-company').on('click', function (){
                var name = $('#name-2').val();
                var place = $('#place-3').val();
                var web = $('#web').val();
                var email = $('#email-2').val();
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
                                    message(message);                    
                                }
                            });            
                        }
                    });
                }else
                    $(this).attr("data-dismiss", ""); 
            });

            $("form[name='uploadForm']").validate({
                rules:{
                    title: "required",
                    name: "required",
                    surname: 'required',
                    email: 'required',
                    phone: 'required'
                },
                messages:{
                    title: "Please enter Title (e.g. Dott, Ing., etc.)",
                    name: "Please enter your Name",
                    surname: "Please enter your Surname",
                    email: "Please enter Email",
                    phone: "Please enter Phone"
                }
            });

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