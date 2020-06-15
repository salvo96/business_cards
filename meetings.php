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
    <script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">     
    <script src="jquery-dateformat.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Arimo|Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="cards.css">

    <link rel="stylesheet" href="fontawesome-stars.css">

    <script src="jquery.barrating.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>

    <style>
        .contain {
            height: 400px; !important
        }
        .card {
            height: 400px;!important
        }
    </style>
    <title>Business Cards - Meetings</title>    
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
                Meetings
                <small>View details of your meetings</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li class="active">Meetings</li>
            </ol>
        </section>

        
        <section class="content container-fluid">
            <div class='row'>
               <!-- <div class="col-md-4"></div> -->
                <div class="col-md-5" style='padding:15px;'><button class="btn btn-primary" onclick="pdfReport2()">Generate Partecipants Report</button></div>                    
                <div class="col-md-5" style='padding:15px;'><button class="btn btn-primary" onclick="pdfReport()">Generate Meetings Report</button></div>

                <div class="col-md-2" style='padding:15px;'>
                    <button class="btn btn-primary" onclick="window.location.href='meeting_create.php'">Create New Meeting</button>
                </div>
            </div>
            <div class='row'>
                <div class="col-md-12"> 
                    <div class="box box-primary"> 
                        <div class="box-header">
                            <h3 class="box-title">Meetings room</h3> 
                        </div>
                        <div class="box-body">
                    
                            <table id="user-meetings" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Role</th>
                                        <th>Title</th>
                                        <th>Partecipants</th>
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
                <div class="col-md-8"> 
                    <div class='row'>
                        <div class="col-md-12"> 
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Partecipants for the selected meeting</h3> 
                                </div>
                                <div class="box-body">
                                    <div class='contain'>
                                        <div style='padding-top: 180px; text-align:center; font-size:30px'>Select a meeting above to view details</div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12"> 
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Meeting Notes</h3> 
                                </div>
                                <div class="box-body">
                                    <div class='row'>
                                        <div class="col-md-5"> 
                                            <textarea id="note" rows="4" cols="45" placeholder="Your notes about this meeting"></textarea>
                                        </div>
                                        <div class="col-md-5"> 
                                            <div class="col-md-6">
                                                <div class='row'>
                                                    <div class="col-md-12">
                                                        <label for="useful">Useful</label>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class="col-md-12">
                                                        <label for="importance">Importance</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class='row'>
                                                    <div class="col-md-12">
                                                    <select id="useful">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class="col-md-12">
                                                    <select id="importance">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                        <div class='col-md-2'>                                            
                                            <button id="save-meeting-info" class="btn btn-primary" disabled>Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"> 
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Partecipant Notes</h3> 
                        </div>
                        <div class="box-body box-profile">
                            <div class='row'>                                
                                <div id='profile_picture'></div>                       
                                <h3 class='profile-username text-center' id="name"></h3>                                                             
                            </div>
                            <div class='row'>
                                <div class="col-md-12">
                                    <textarea id="note-2" rows="4" cols="50" placeholder="Your notes about this partecipant"></textarea>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-6">
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <label for="professionality">Professionality</label>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <label for="impression">Impression</label>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <label for="availability">Availability</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <select id="professionality">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <select id="impression">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <select id="availability">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='row'>
                                <div class="col-md-12">
                                    <button id="save-partecipant-info" class="btn btn-primary" disabled>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- MODALS-->

    <div id="meeting-summary" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Meeting Overview</h4>
            </div>
            <div class="modal-body">
                <h3 id="modal-content-title"></h3>
                <h4 id="modal-content-date"></h4>
                <h4 id="modal-content-time"></h4>
                <h4 id="modal-content-place"></h4>
                <p id='modal-content-topic' style='font-style: italic;'></p>
                <h4>Partecipants</h4> 
                    <table id="partecipants" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Partecipation</th>
                            </tr>
                        </thead>
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>

        </div>
    </div>

    <div id="meeting-update" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Meeting Update</h4>
            </div>
            <div class="modal-body"> 
                <form role="form" id="meeting-form" name="meeting-form">           
                    <label for="title">Title</label>
                    <input id="title" class="form-control" name="title" type="text">
                    <label for="date">Date</label>
                    <input id="date" class="form-control" name="date" type="date" min="1900-01-01" max="2100-12-31">
                    <label for="time">Time</label>
                    <input id="time" class="form-control" name="time" type="time">
                    <label for="address">Address</label>
                    <input id="address" class="form-control" name="address" type="text">
                    <input type="button" style='display:block; margin-top:10px;' class="btn btn-primary" style='margin-top:10px;' value="Set Address*" onclick="codeAddress()">
                    <label for="topic">Topic</label>
                    <textarea id="topic" class="form-control" rows="4" cols="50" placeholder="Your notes about this meeting"></textarea> 
                    <div id="map" style="width:100%;height:400px"></div>  
                </form>          
            </div>
            <div class="modal-footer">
                <button id="update-send" type="button" class="btn btn-primary">Update</button>
            </div>
            </div>

        </div>
    </div>

    <div id="meeting-invite" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invite other partecipants</h4>
            </div>
            <div class="modal-body">            
                <table id="users-table" class="table table-striped table-bordered" style="width:100%">
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
            </div>
            <div class="modal-footer">
                <button id="invite-send" type="button" class="btn btn-primary" data-dismiss="modal">Invite</button>
            </div>
            </div>

        </div>
    </div>

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

        <div class="modal fade" id="modal-delete-2">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Action</h4>
              </div>
              <div class="modal-body">
                <p>Are you sure to leave this meeting?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id='confirm-delete-2' class="btn btn-primary" data-dismiss="modal">Confirm</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <?php include("_message.php"); ?>

</section>
</div>
</div>

    <script>
        var meeting_id;
        var card_id;

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
    $(document).ready(function() {
                var table = $('#user-meetings').DataTable( {
                "ajax": "/homework/user_meetings_manage.php",
                "searching": false,
                select: 'single',
                "pageLength": 2,
                "lengthChange": false,                
                "order": [[ 2, "asc" ]]
            } );
            var table2;
            var table3;            

        table.column( 0 ).visible( false );
        table.on('user-select', function (e, dt, type, cell, originalEvent) {
                if ($(cell.node()).parent().hasClass('selected')) {
                    e.preventDefault();
                }
        });//disabilita la deselezione della tabella

        $('#user-meetings tbody').on( 'click', '.fa-eye', function () {
            var data = table.row( $(this).parents('tr') ).data();
            meeting_id = data[0];
            $.ajax({
                    type: "POST",
                    url: "/homework/meeting_overview.php",
                    data: {meeting_id:meeting_id},
                    dataType: "json",
                    success: function(msg)
                    {
                        var title = msg.title;
                        var place = msg.place;
                        var date = $.format.date(msg.date, "dd/MM/yyyy");
                        var time = $.format.date(msg.date, "HH:mm");
                        var topic = msg.topic;
                        $('#modal-content-title').text(title); 
                        $('#modal-content-date').text(date); 
                        $('#modal-content-time').text(time); 
                        $('#modal-content-place').text(place);
                        $('#modal-content-topic').text(topic);                        
                    }
                });  
            if($.fn.dataTable.isDataTable('#partecipants'))
                table2.destroy();

            table2 = $('#partecipants').DataTable( {
                "ajax": {
                    "type": 'POST',
                    "url": "/homework/meeting_partecipants.php",
                    "data": {
                        "meeting_id": meeting_id
                    }
                },
                "searching": false,
                "pageLength": 2,
                "lengthChange": false,                
                "order": [[ 0, "asc" ]]
            } );
                    
        } );

        $('#user-meetings tbody').on( 'click', '.fa-pencil-square', function () {
            var data = table.row( $(this).parents('tr') ).data();
            meeting_id = data[0];
            $.ajax({
                    type: "POST",
                    url: "/homework/meeting_overview.php",
                    data: {meeting_id:meeting_id},
                    dataType: "json",
                    success: function(msg)
                    {
                        var title = msg.title;
                        var place = msg.place;
                        var date = $.format.date(msg.date, "yyyy-MM-dd");
                        var time = $.format.date(msg.date, "HH:mm");
                        var topic = msg.topic;
                        lat = parseFloat(msg.lat);
                        lng = parseFloat(msg.lng);
                        map.setCenter({lat:lat, lng:lng});
                        var marker = new google.maps.Marker({
                            map: map,
                            position: {lat:lat, lng:lng}
                        });                        
                        $('#title').val(title); 
                        $('#date').val(date); 
                        $('#time').val(time); 
                        $('#address').val(place); 
                        $('#topic').val(topic);                     
                    }
            });                      
        } );

        $('#update-send').on('click', function (){
            var title = $('#title').val();
            var place = $('#address').val();
            var date = $('#date').val()+" "+$('#time').val();
            var topic = $('#topic').val();
            var form = $("#meeting-form");
            if(form.valid()){
                $(this).attr("data-dismiss", "modal");
                $.ajax({
                        type: "POST",
                        url: "/homework/meeting_update.php",
                        data: {meeting_id: meeting_id, title: title, place: place, date: date, topic: topic, lat:lat, lng:lng},
                        dataType: "html",
                        success: function(msg)
                        {
                            $('#user-meetings').DataTable().ajax.reload(); 
                            message(msg);               
                        }
                }); 
            }
            else
                $(this).attr("data-dismiss", "");

        });

        $('#user-meetings tbody').on( 'click', '.fa-remove', function () {
            var data = table.row( $(this).parents('tr') ).data();
            meeting_id = data[0];               
        } );

        $('#confirm-delete').on('click', function(){
            $.ajax({
                    type: "POST",
                    url: "/homework/meeting_delete.php",
                    data: {meeting_id:meeting_id},
                    dataType: "html",
                    success: function(msg)
                    {
                        $('#user-meetings').DataTable().ajax.reload(); 
                        message(msg);                                              
                    }
            });
        });

        $('#user-meetings tbody').on( 'click', '.fa-sign-in', function () {
            var data = table.row( $(this).parents('tr') ).data();
            meeting_id = data[0];
            if($.fn.dataTable.isDataTable('#users-table'))
                table3.destroy();
            table3 = $('#users-table').DataTable( {
                "ajax": {
                    "url": "/homework/i_users_list.php",
                    "type": "POST",
                    "data": {meeting_id: meeting_id},
                },
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<input class='checkusers' type='checkbox'>",
                    "searchable": false
                }],
                "pageLength": 2,
                "lengthChange": false,
                "order": [[ 1, "asc" ]]
            } );
            table3.column( 0 ).visible( false );
               
        } );

        $('#users-table').on( 'click', 'input', function () {

            var data = table3.row( $(this).parents('tr') ).data();
            var input = $(this);
            input.attr("value", data[0]);                 
        } );

        $('#invite-send').on('click', function (){
            var user_invited = Array();

            $(".checkusers:checked").each(function() {
                user_invited.push($(this).val());
            }); 
            $.ajax({
                type: "POST",
                url: "/homework/meeting_new_invite.php",
                data: {meeting_id: meeting_id, user_invited:user_invited},
                dataType: "html",
                success: function(msg)
                {
                    message(msg);
                }
            });

        });


        $('#user-meetings tbody').on( 'click', '.fa-sign-out', function () {
            var data = table.row( $(this).parents('tr') ).data();
            meeting_id = data[0];               
        } );

         $('#confirm-delete-2').on('click', function(){
            $.ajax({
                    type: "POST",
                    url: "/homework/meeting_leave.php",
                    data: {meeting_id:meeting_id},
                    dataType: "html",
                    success: function(msg)
                    {
                        $('#user-meetings').DataTable().ajax.reload(); 
                        message(msg);                                              
                    }
            }); 
        });


        $('#user-meetings tbody').on( 'click', 'tr', function () {
            var data = table.row($(this)).data();
            meeting_id = data[0];
            
            $('#profile_picture').html("");
            $('#name').text('');
            $('#note-2').val('');       //quando clicco una nuova riga della tabella meetings devo azzerare le info del partecipante: nessuno è stato selezionato
            $('#professionality').barrating('clear');
            $('#impression').barrating('clear');
            $('#availability').barrating('clear');
            $('#useful').barrating('clear');
            $('#importance').barrating('clear');
            $('#save-partecipant-info').prop('disabled', true);            
            $.ajax({
                type: "POST",
                url: "/homework/meeting_business_cards.php",
                data: {meeting_id:meeting_id},
                dataType: "html",
                success: function(msg)
                {
                    $('.contain').html(msg);                                                                    
                }
            }) 

            $.ajax({
                type: "POST",
                url: "/homework/meeting_partecipate_info.php",
                data: {meeting_id:meeting_id},
                dataType: "json",
                success: function(msg)
                {
                    var note = msg.note;
                    var useful = msg.useful;
                    var importance = msg.importance;
                    $('#note').val(note); 
                    $('#useful').barrating('set', useful);
                    $('#importance').barrating('set', importance);                                                                   
                }
            }) 
            $('#save-meeting-info').prop('disabled', false);        

        });

        $('#save-meeting-info').on('click', function() {
            var note = $("#note").val();
            var useful = $("#useful").val();
            var importance = $("#importance").val();
            $.ajax({
                    type: "POST",
                    url: "/homework/meeting_partecipate_info_add.php",
                    data: {note:note, useful:useful, importance:importance, meeting_id: meeting_id},
                    dataType: "html",
                    success: function(msg)
                    {
                        message(msg); 
                    }
            });
        }); 

        $(document).on('click', '.radioButton', function() {
            $('#profile_picture').html("");
            $('#name').text('');
            $('#note-2').val('');      
            $('#professionality').barrating('clear');
            $('#impression').barrating('clear');
            $('#availability').barrating('clear');
            $(".radioButton").prop('checked', false);
            $(this).prop('checked', true);
            card_id = $(this).attr("data-card_id");
            $.ajax({
                type: "POST",
                url: "/homework/partecipant_notes.php",
                data: {card_id:card_id, meeting_id:meeting_id},
                dataType: "json",
                success: function(msg)
                {   
                    var name = msg.name;
                    var note = msg.note;
                    var professionality = msg.professionality;
                    var impression = msg.impression;
                    var availability = msg.availability;
                    var p_user_id = msg.p_user_id;
                    $('#profile_picture').html("<img src='profile_image_p.php?p_user_id="+p_user_id+"' class='profile-user-img img-responsive img-circle' alt='No picture'>")
                    $('#name').text(name);
                    $('#note-2').val(note); 
                    $('#professionality').barrating('set', professionality);
                    $('#impression').barrating('set', impression);
                    $('#availability').barrating('set', availability);                                                                      
                }
            }) 
            $('#save-partecipant-info').prop('disabled', false);

        });  


        $('#save-partecipant-info').on('click', function() {
            var note = $('#note-2').val(); 
            var professionality = $('#professionality').val();
            var impression = $('#impression').val();
            var availability = $('#availability').val(); 
            $.ajax({
                    type: "POST",
                    url: "/homework/partecipant_notes_add.php",
                    data: {note:note, professionality: professionality, impression: impression, availability: availability, card_id:card_id, meeting_id:meeting_id},
                    dataType: "html",
                    success: function(msg)
                    {
                        message(msg); 
                    }
            });
        });  

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

    } );        
    
    function pdfReport2(){ 
        if(meeting_id == undefined)
            message("Select a meeting below");
        else{     
            $.ajax({
                type: "POST",
                data: {meeting_id: meeting_id},
                url: "/homework/meetings_partecipants_report.php",
                dataType: "json",
                success: meetingReport2, 
                                
            }); 
        }       
    }

    function meetingReport2(msg) {
        if(msg == "Partecipant Meeting")
            message("You are not the manager of this meeting");
        else
        if(msg.length>0){
            var doc = new jsPDF();
            var author = "<?php echo $_SESSION["user"]; ?>";
            doc.setProperties({
                title: 'Business Card Meeting Partecipants Report',
                subject: 'Meeting Partecipants report',		
                author: author,
                creator: 'Business Cards'
            });

            for(element in msg){  
                doc.setFont("helvetica");
                doc.setFontType("bold");
                doc.setFontSize(20);
                doc.text(100, 20, 'Business Card', null, null, 'center');
                doc.setFontType("normal");
                doc.setFontSize(18);
                doc.text(100, 30, 'Social for your Network', null, null, 'center');
                doc.setLineWidth(0.5);
                doc.line(20, 40, 190, 40);
                doc.setFontSize(12);
                doc.text(20, 50, 'Meeting Partecipants report: '+(parseInt(element)+1)+"/"+msg.length+" by "+author);
                doc.setFontType("bold");
                doc.setFontSize(14);
                doc.text("Meeting Information", 20, 70);
                doc.setFontType("normal");
                doc.setFontSize(12);
                var date = $.format.date(msg[element].date, "dd/MM/yyyy");
                var time = $.format.date(msg[element].date, "HH:mm");
                doc.text("Meeting Title: "+msg[element].meeting, 20, 90);
                doc.text("Date: "+date, 20, 100);
                doc.text("Time: "+time, 20, 110);
                doc.text("Place: "+msg[element].place, 20, 120);
                var topic = msg[element].topic;
                if(topic != null)
                    doc.text("Topic: "+topic, 20, 130);
                doc.setFontType("bold");
                doc.setFontSize(14);
                doc.text("Partecipant Information", 20, 150);
                doc.setFontType("normal");
                doc.setFontSize(12);
                doc.text("Partecipant: "+msg[element].name+" "+msg[element].surname, 20, 170);
                var note = msg[element].note;
                var professionality = msg[element].professionality;
                var impression = msg[element].impression;
                var availability = msg[element].availability;
                if(note == null) note = "Not Present";
                if(professionality == null) professionality = "Not Present";
                if(impression == null) impression = "Not Present";
                if(availability == null) availability = "Not Present";
                if(msg[element].Past_meeting=='1'){
                    doc.text("Notes: "+note, 20, 180);
                    doc.text("Professionality: "+professionality, 20, 190);
                    doc.text("Impression: "+impression, 20, 200);
                    doc.text("Availability: "+availability, 20, 210);
                }
                else{
                    doc.setFontType("italic");
                    doc.setFontSize(14);
                    doc.text(100, 180, "Future meeting: Partecipant Notes not present yet", null, null, 'center');
                }
                doc.setFontSize(10);
                doc.text("Report printed: "+$.format.date(new Date(), "dd/MM/yyyy HH:mm"), 10, 290);
                if(element < msg.length-1) doc.addPage('a4'); 
            }
            doc.save('Partecipants_Report.pdf');
        }
        else
            message("Partecipant Evaluation not present and/or future meeting");
            
    }
    
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyArAXrjA7lgdtsZmx-vfwz6EoKIVIhqQ30&callback=initialize"></script>

    <!--INIZIALIZZAZIONE DEL BAR RATING PER LA VALUTAZIONE MEETING/PARTECIPANTI-->
    <script type="text/javascript">
        $(function() {
            $('#professionality').barrating({
                theme: 'fontawesome-stars',
                initialRating: -1
            });
            $('#impression').barrating({
                theme: 'fontawesome-stars',
                initialRating: -1
            });
            $('#availability').barrating({
                theme: 'fontawesome-stars',
                initialRating: -1
            });
            $('#useful').barrating({
                theme: 'fontawesome-stars',
                initialRating: -1
            });
            $('#importance').barrating({
                theme: 'fontawesome-stars',
                initialRating: -1
            });
        });
    </script>
    <!----------------------------                                   ----------------------------->

    <!--Script per la lettura dal DB delle informazioni dei partecipanti (valutazione) per il singolo meeting organizzato-->
    <script>

    function pdfReport(){        
        $.ajax({
            type: "POST",
            url: "/homework/meetings_report.php",
            dataType: "json",
            success: meetingReport, 
                    
        });         
    }

    function meetingReport(msg) {
        if(msg.length>0){
            var doc = new jsPDF();
            var author = "<?php echo $_SESSION["user"]; ?>";
            doc.setProperties({
                title: 'Business Card Meetings Report',
                subject: 'Organized Meeting Partecipants report',		
                author: author,
                creator: 'Business Cards'
            });

            for(element in msg){  
                doc.setFont("helvetica");
                doc.setFontType("bold");
                doc.setFontSize(20);
                doc.text(100, 20, 'Business Card', null, null, 'center');
                doc.setFontType("normal");
                doc.setFontSize(18);
                doc.text(100, 30, 'Social for your Network', null, null, 'center');
                doc.setLineWidth(0.5);
                doc.line(20, 40, 190, 40);
                doc.setFontSize(12);
                doc.text(20, 50, 'Organized Meetings Partecipants report: '+(parseInt(element)+1)+"/"+msg.length+" by "+author);
                doc.setFontType("bold");
                doc.setFontSize(14);
                doc.text("Meeting Information", 20, 70);
                doc.setFontType("normal");
                doc.setFontSize(12);
                var date = $.format.date(msg[element].Date, "dd/MM/yyyy");
                var time = $.format.date(msg[element].Date, "HH:mm");
                doc.text("Meeting Title: "+msg[element].Meeting_Title, 20, 90);
                doc.text("Date: "+date, 20, 100);
                doc.text("Time: "+time, 20, 110);
                doc.text("Place: "+msg[element].Place, 20, 120);
                var topic = msg[element].Topic;
                if(topic != null)
                    doc.text("Topic: "+topic, 20, 130);
                doc.setFontType("bold");
                doc.setFontSize(14);
                doc.text("Partecipant Information", 20, 150);
                doc.setFontType("normal");
                doc.setFontSize(12);
                doc.text("Partecipant: "+msg[element].Title+" "+msg[element].Name+" "+msg[element].Surname, 20, 170);
                doc.text("Phone: "+msg[element].Phone, 20, 180);
                doc.text("Email: "+msg[element].Email, 20, 190);
                doc.setFontType("bold");
                doc.setFontSize(14);            
                doc.text("Meeting Notes", 20, 210);
                doc.setFontType("normal");
                doc.setFontSize(12);
                var note = msg[element].Note;
                var useful = msg[element].Useful;
                var importance = msg[element].Importance;
                if(note == null) note = "Not Present";
                if(useful == null) useful = "Not Present";
                if(importance == null) importance = "Not Present";
                if(msg[element].Past_meeting=='1'){
                    doc.text("Notes: "+note, 20, 230);
                    doc.text("Useful: "+useful, 20, 240);
                    doc.text("Importance: "+importance, 20, 250);
                }
                else{
                    doc.setFontType("italic");
                    doc.setFontSize(14);
                    doc.text(100, 230, "Future meeting: Meeting Notes not present yet", null, null, 'center');
                }
                doc.setFontSize(10);
                doc.text("Report printed: "+$.format.date(new Date(), "dd/MM/yyyy HH:mm"), 10, 290);
                if(element < msg.length-1) doc.addPage('a4'); 
            }
            doc.save('Meetings_Report.pdf');
        }
        else
            message("You have not organized any meeting yet!");
            
    }
    </script>
</body>
</html>