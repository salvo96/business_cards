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
    <link href="https://fonts.googleapis.com/css?family=Arimo|Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="cards.css">
    <script src='jquery.steps.js'></script>
    <link href="jquery.steps.css" rel="stylesheet">
   <!-- <link href="main.css" rel="stylesheet">
    <link href="normalize.css" rel="stylesheet">-->
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js"></script>
    <title>Business Cards - Create New Meeting</title>
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
                Create New Meeting
                <small>Meeting Creation Wizard</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li><a href="meetings.php">Meetings</a></li>
                <li class="active">Create New Meeting</li>
            </ol>
        </section>

        <section class="content container-fluid">
        <div class='row'>    
            <div class="col-md-12">
                <div class="box box-primary">
                <div class="box-body">

            <form role="form" id="meeting-form" name="meeting-form"> 
                <div id="meeting-steps">
                    
                    <h3>Meeting Info</h3>
                    <section>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5">
                                <label for="title">Title</label>
                                <input id="title" class="form-control" name="title" type="text">
                                <label for="date">Date</label>
                                <input id="date" class="form-control" name="date" type="date" min="1900-01-01" max="2100-12-31">
                                <label for="time">Time</label>
                                <input id="time" class="form-control" name="time" type="time">
                                <label for="address">Address</label>
                                <input id="address" class="form-control" name="address" type="text">
                                <input type="button" class="btn btn-primary" style='margin-top:10px;' value="Set Address*" onclick="codeAddress()">
                                <label for="topic">Topic</label>
                                <textarea id="topic" class="form-control" rows="4" cols="50" placeholder="Your notes about this meeting"></textarea>
                            </div>
                            <div class="col-lg-5"> 
                                <div id="map" style="width:100%;height:400px"></div>  
                            </div>
                        </div>  
                    </div>     
                    </section>
                    
                    <h3>Partecipants</h3>
                    
                    <section>
                        <table id="meeting-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </section>
                    

                    <h3>Choose Profile</h3>
                    <section>
                        <div class='row'>
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
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
                            mysqli_stmt_bind_param($stmt, "i",$user_id);
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
                                                        <li style='display: block;' class='card-element'>".$row["phone"]."</li>
                                                        <li style='display: block;' class='card-element'>".$row["email"]."</li>
                                                    </ul>
                                                    <div id='check'><input class='radioButton' type='radio' data-card_id=".$row["card_id"].">Select this card</div>
                                                </div>
                                            </div>
                                        </div>";
                            }
                            mysqli_stmt_close($stmt);
                                
                            
                            
                            echo "</div>";

                            if($active == 1)
                                echo "<div style='padding-top:200px;text-align:center;font-size:20px'>You have not created a business card yet</div>";
                            else 
                            echo '<a class="left carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                            </a>'; ?>
                        </div>
                        </div>
                        <!--               -->
                        </div>
                        </div>
                    </section>                        
                </div>
            </form>
            </div>
            </div>
            </div>
            <?php include("_message.php"); ?>
        </section>
    </div>
</div>

<script>
    var card_id; 
    var lat, lng;
    
    /*Google Maps script*/
    function initialize() {
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(41.890251, 12.492373);
            var mapOptions = {
            zoom: 12,
            center: latlng
            }
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
        }

        function codeAddress() {
            var address = document.getElementById('address').value;
            geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == 'OK') {
                lat = results[0].geometry.location.lat();
                lng = results[0].geometry.location.lng();
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else {
                message('Geocode was not successful for the following reason: ' + status);
            }
            });
        }
    /*__________________________________________________________ */
    $("#meeting-steps").steps({
        headerTag: "h3",
        bodyTag: "section",
        stepsOrientation: "horizontal",
        transitionEffect: "fade",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex)
            {
                if (currentIndex > newIndex)    //setto se è possibile ritornare indietro nel form
                {
                    return true;
                }
                if (currentIndex < newIndex)    //setto se è possibile andare avanti nel form
                {
                    var form = $("#meeting-form");
                    if(!form.valid() || lat === undefined || lng === undefined)
                        return false;
                    else
                        return true;
                }
                
                
            },
        onFinishing: function (event, currentIndex) //prima di concludere il wizard controllo che l'utente abbia selezionato una card per il proprio profilo
            { 
                if($(".radioButton:checked").length==1)
                    return true;
                else
                    return false;
            }, 
        onFinished: function (event, currentIndex) 
            { 
                var title = $("#title").val();
                var date = $("#date").val();
                var time = $("#time").val();
                var address = $("#address").val();
                var topic = $('#topic').val();
                var user_invited = Array();

                $(".checkusers:checked").each(function() {
                    user_invited.push($(this).val());
                }); 
                $.ajax({
                    type: "POST",
                    url: "/homework/meeting_insert.php",
                    data: {title:title, date:date, time:time, address:address, topic:topic, lat:lat, lng:lng, card_id:card_id, user_invited:user_invited},
                    dataType: "html",
                    success: function(msg)
                    {                        
                        message(msg);                 
                        setInterval(function(){
                            $(window.location).attr('href', 'meetings.php');
                        },2000);  
                    }
                });

            }
    });
    
$(document).ready(function() {
    var table = $('#meeting-table').DataTable( {
        "ajax": "/homework/users_list.php",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<input class='checkusers' type='checkbox'>",
            "searchable": false
        }],
        "order": [[ 1, "asc" ]]
    } );

    $('#meeting-table tbody').on( 'click', 'input', function () {
        var data = table.row( $(this).parents('tr') ).data();
        var input = $(this);
        input.attr("value", data[0]);                 
    } );

    table.column( 0 ).visible( false );
} );

 $(document).ready(function() {
    $('.radioButton').on('click', function() {
        $(".radioButton").prop('checked', false);
        $(this).prop('checked', true);
        card_id = $(this).attr("data-card_id");
    });
    
} );

$("form[name='meeting-form']").validate({
    rules:{
        title: "required",
        date: "required",
        time: "required",
        address: "required"
    },
    messages:{
        title: "Please enter title",
        date: "Please enter date",
        time: "Please enter time",
        address: "Please enter address"
    }

});
    
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyArAXrjA7lgdtsZmx-vfwz6EoKIVIhqQ30&callback=initialize"></script>
</body>
</html>