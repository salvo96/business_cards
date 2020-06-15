<?php
include("database.php");

session_start();

if(isset($_COOKIE["user_id"])){
    $_SESSION["user_id"] = $_COOKIE["user_id"];
    $_SESSION["user"] = $_COOKIE["user"];
    $_SESSION["activation_date"] = $_COOKIE["activation_date"];
}

if(isset($_GET["meeting"])&&isset($_GET["user"])){
    $meeting_id = $_GET["meeting"];
    $user = $_GET["user"];
}
else{
    die("Access forbidden!");
}

if(!isset($_SESSION["user_id"])){
    header("Location:index.php?user=".$user."&meeting=".$meeting_id);
}
else{
    $user_id = $_SESSION["user_id"];
    if($user_id != $user)
        die("Access forbidden!");
}    
    

$conn = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($conn, $db_database);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    
    <title>Business Cards - Account Info</title>
    <?php include("_common_header.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>     
    <link href="https://fonts.googleapis.com/css?family=Arimo|Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="cards.css">
    
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
                Your Meeting Invites
                <small>Manage your invite</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li class="active">Your Meeting Invites</li>
            </ol>
        </section>

        <section class="content container-fluid">
            <!--PAGE CONTENT-->
            <div class='row'>

                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">

                            <table id="invites-table" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Organizer</th>
                                        <th>Title</th>
                                        <th>Place</th>
                                        <th>Date</th>
                                        <th>Hour</th>
                                        <th>Accept</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="meeting-card" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Select your Meeting Card</h4>
                    </div>
                    <div class="modal-body">

                        <!--Business Cards Carousel -->
                        <div class="contain"> 
                        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">   

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                            <!--Parte generata dinamicamente-->
                            
                            <?php

                            $stmt = mysqli_prepare($conn, "SELECT C.card_id AS card_id, C.photo AS photo, CONCAT(C.title, ' ',C.name,' ', C.surname) AS name, W.role AS role, E.name AS company, S.title AS education, C.phone AS phone, C.email AS email
                            FROM ((cards C JOIN work_experience W ON C.work_experience_id = W.work_experience_id) JOIN company E on W.company_id = E.company_id) JOIN education_experience S ON C.education_experience_id = S.education_experience_id
                            WHERE C.user_id = ?");
                            mysqli_stmt_bind_param($stmt, "i", $user_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);                 
                                                      
                            $active = 1;

                            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                if($active == 1){
                                    echo "<div class='item active'>";
                                    $active = 0;
                                }
                                else
                                    echo "<div class='item'>";
                                    echo "<div class='card'>
                                                <div class='back'>
                                                    <img src='card_image.php?card_id=".$row["card_id"]."' class='profile-user-img img-responsive img-circle' alt='No picture'>
                                                    <h1 class='card-header-1'>".$row["name"]."<span>".$row["role"]."<i>@</i>".$row["company"]."</span></h1>
                                                    <h2 class='card-header-2'>".$row["education"]."</h2>
                                                    <ul class='card-list'>
                                                        <li class='card-element'>".$row["phone"]."</li>
                                                        <li class='card-element'>".$row["email"]."</li>
                                                    </ul>
                                                    <div id='check'><input class='radioButton' type='radio' data-card_id=".$row["card_id"].">Select this card</div>
                                                </div>
                                            </div>
                                        </div>";
                            }
                            mysqli_stmt_close($stmt);    
                            
                        echo '</div>';

                            if($active == 1)
                                echo "<div style='padding-top:200px;text-align:center;font-size:20px'>You have not created a business card yet</div>";
                            else
                                echo '
                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                            </a>'; ?>
                        </div>
                        </div>         
                            
                    </div>
                    <div class="modal-footer">
                        <button id="save-choice" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                    </div>
                    </div>

                </div>
            </div>

            <?php include("_message.php"); ?>

        </section>
            <!-- /.content -->
    </div>

</div>

    <script>
    var meeting_id = "<?php echo $meeting_id ?>";
        $(document).ready(function() {
            var table1 = $('#invites-table').DataTable( {
                "ajax": {
                    "url": "my_invite.php",
                    "type": "POST",
                    "data": {meeting_id: meeting_id}
                },
                "columnDefs": [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<button id='yes' class='btn btn-block btn-success btn-xs' data-toggle='modal' data-target='#meeting-card'>Yes</button> <button id='no' class='btn btn-block btn-danger btn-xs' >No</button>",
                "searchable": false
            }],
                "searching": false,
                'info': false,
                'lengthChange': false,
                'ordering': false,
                'paging': false,
                "language": {
                    "emptyTable": "There are not invites for you!"
                }
            } );
            table1.column( 0 ).visible( false );

            $('#invites-table tbody').on('click','#no', function(){
                var data = table1.row( $(this).parents('tr') ).data();
                meeting_id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/homework/meeting_invite_decline.php",
                    data: {meeting_id:meeting_id},
                    dataType: "html",
                    success: function(msg)
                    {
                        message(msg);  
                        setInterval(function(){
                            $(window.location).attr('href', 'dashboard.php');
                        },2000);              
                    }
                });  

            });

            $('#invites-table tbody').on('click','#yes', function(){
                $(".radioButton").prop('checked', false);
                var data = table1.row( $(this).parents('tr') ).data();
                meeting_id = data[0];
            });

            $('#save-choice').on('click', function(){
                if($(".radioButton:checked").length==1)
                    $.ajax({
                        type: "POST",
                        url: "/homework/meeting_invite_accept.php",
                        data: {meeting_id:meeting_id, card_id:card_id},
                        dataType: "html",
                        success: function(msg)
                        {
                            message(msg); 
                            setInterval(function(){
                                $(window.location).attr('href', 'dashboard.php');
                            },2000);               
                        }
                    });  
                else
                    message("Select a card for this meeting");

            });

            $('.radioButton').on('click', function() {
                    $(".radioButton").prop('checked', false);
                    $(this).prop('checked', true);
                    card_id = $(this).attr("data-card_id");
                });

        });


    </script>


    
</body>
</html>