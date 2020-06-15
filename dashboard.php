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
    <script src='../homework/Chart.min.js'></script>
    <link href="https://fonts.googleapis.com/css?family=Arimo|Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="cards.css">
    <link rel="stylesheet" href="fontawesome-stars.css">
    <script src="jquery.barrating.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type='text/javascript' charset='utf8' src='https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js'></script>
    <script type='text/javascript' charset='utf8' src='https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.min.js'></script>   

    <title>Business Cards - Dashboard</title>
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
                    Dashboard
                    <small>Overview area of activities</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </section>

            <section class="content container-fluid">

        <!--------------------------
            | Your Page Content Here |
            --------------------------> 
        <div class='row'>    
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div id='profile_picture'><img src='profile_image.php' class='profile-user-img img-responsive img-circle' alt='No picture'></div>
                        <?php 
                        $stmt = mysqli_prepare($conn, "SELECT name, surname FROM users WHERE user_id = ?");
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);                        

                        if($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
                            echo '<h3 class="profile-username text-center">'.$row["name"].' '.$row["surname"].'</h3>';
                        mysqli_stmt_close($stmt);
                        ?>     

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Meeting invites</span>
                        <span class="info-box-number">You have <span id='new_invites'></span> new invites</span>
                    </div>
                    <button id='view_invites' class="btn btn-block btn-primary" data-toggle='modal' data-target='#invites'>View Invites</button>

                </div>

                
                
            </div>

            <div class="col-md-6">
                <div class="box"> 
                    <div class="box-header">
                        <h3 class="box-title">Meetings</h3> 
                    </div>
                    <div class="box-body">
                        <table id="user-meetings" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Title</th>
                                    <th>Partecipants</th>
                                    <th>Place</th>
                                    <th>Evaluation</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>    
            </div>    
        </div><!--Row-->

        <div class='row'>
        <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header">
              <i class="fa fa-envelope"></i>
              <h3 class="box-title">Quick Email</h3>
            </div>
            <div class="box-body">
              <form action="#" id='quick_mail' name='quick_mail' method="post">
                <div class="form-group">
                  <input type="email" id='tags' class="form-control" name="tags" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" id='subject' class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <textarea id='text' class="textarea" placeholder="Message" name='text'
                            style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
              </form>
            </div>
            <div class="box-footer clearfix">
              <button type="button" class="pull-right btn btn-primary" id="sendEmail">Send
                <i class="fa fa-arrow-circle-right"></i></button>
            </div>
          </div>

        </div>

            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last year organized meetings</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="barChart" style="height:230px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last meeting partecipants</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                        <canvas id="pieChart" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
     
<!--MODALS-->
    <div id="invites" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
                <button id="invites-close-2" type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Your Meeting Invites</h4>
            </div>
            <div class="modal-body">
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
            <div class="modal-footer">
                <button id="invites-close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--In corrispondenza della pressione di questo pulsante devo aggiornare la tabella user-meetings-->
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
</div><!--content wrapper-->
</div>

    <script>
    var meeting_id; 
    var card_id;
    var facebook_alert = "<?php if(isset($_GET["facebook_assoc"])) 
                                    echo $_GET["facebook_assoc"]; 
                                else
                                    echo "";?>";
    setInterval(invites, 2000);//eseguo la lettura degli inviti ogni 2 secondi
        
        $(document).ready(function() {
            if(facebook_alert == "1")
                message("Facebook account association completed");
            invites();//eseguo la lettura degli inviti al caricamento della pagina
            var table = $('#user-meetings').DataTable( {
                "ajax": "/homework/user_meetings.php",
                "pageLength": 2,
                "lengthChange": false,                
                "searching": false,
                "order": [[ 1, "asc" ]]
            } );

            var table1 = $('#invites-table').DataTable( {
                "ajax": "/homework/my_invites.php",
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
                        //feedback da inserire                            
                        $('#invites-table').DataTable().ajax.reload(); 
                        message(msg);               
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
                            //feedback da inserire                            
                            $('#invites-table').DataTable().ajax.reload(); 
                            message(msg);               
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

        $('#invites-close').on('click',function(){
            $('#user-meetings').DataTable().ajax.reload(); 
        });
        $('#invites-close-2').on('click',function(){
            $('#user-meetings').DataTable().ajax.reload(); 
        });
        $('#view_invites').on('click',function(){
            $('#invites-table').DataTable().ajax.reload(); 
        });

        /***************************Retrieve dati autocompletamento indirizzi mail e invio Quick Mail********************/
        $.ajax({
            type: "POST",
            url: "/homework/cards_email_address.php",
            dataType: "json",
            success: function(msg)
            {
                $( "#tags" ).autocomplete({
                    source: msg
                });                                     
            }
        }); 

        $('#sendEmail').on('click',function(){
            var email = $("#tags").val();
            var subject = $("#subject").val();
            var text = $("#text").val();
            var form = $("#quick_mail");  
            if(form.valid())
                $.ajax({
                    type: "POST",
                    url: "/homework/send_quick_mail.php",
                    data: {email: email, subject: subject, text: text},
                    dataType: "html",
                    success: function(msg)
                    {
                        message(msg);  
                        $("#tags").val('');
                        $("#subject").val('');
                        $("#text").val('');             
                    }
                });  
        });

        
        /********************************************************/ 

        $("form[name='quick_mail']").validate({
            rules:{
                tags: "required",
                subject: "required",
                text: "required"
            },
            messages:{
                tags: "Please enter mail recipient",
                subject: "Please mail subject",
                text: "Please enter mail text"
            }
        });
        } );

        function invites() {
            $.ajax({
                    type: "POST",
                    url: "/homework/user_meeting_invites.php",
                    dataType: "html",
                    success: function(msg)
                    {                          
                        $('#new_invites').text(msg);             
                    }
                });  
            
        }

        //BAR CHART DATA UPDATE
    $(function () { 
        $.ajax({
        type: "POST",
        url: "/homework/stats_1.php",
        dataType: "json",
        success: function(msg)
        {     
            var areaChartData = {
            labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                label               : 'Digital Goods',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : msg
                }
            ]
            }

            var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
            var barChart                         = new Chart(barChartCanvas)
            var barChartData                     = areaChartData
            barChartData.datasets[0].fillColor   = '#00a65a'
            barChartData.datasets[0].strokeColor = '#00a65a'
            barChartData.datasets[0].pointColor  = '#00a65a'
            var barChartOptions                  = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : false,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing       : 1,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to make the chart responsive
            responsive              : true,
            maintainAspectRatio     : true
            }

            barChartOptions.datasetFill = false
            barChart.Bar(barChartData, barChartOptions)   
              
        }
        
        })

         $.ajax({
            type: "POST",
            url: "/homework/stats_2.php",
            dataType: "json",
            success: function(msg)
            { 
                var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                var pieChart       = new Chart(pieChartCanvas)
                var PieData        = [
                {
                    value    : msg.partecipants,
                    color    : '#00a65a',
                    highlight: '#00a65a',
                    label    : 'Partecipants'
                },
                {
                    value    : msg.not_partecipants,
                    color    : '#f56954',
                    highlight: '#f56954',
                    label    : 'Not Partecipants'
                },
                {
                    value    : msg.no_response,
                    color    : '#f39c12',
                    highlight: '#f39c12',
                    label    : 'No Response'
                }
                ]
                var pieOptions     = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke    : true,
                //String - The colour of each segment stroke
                segmentStrokeColor   : '#fff',
                //Number - The width of each segment stroke
                segmentStrokeWidth   : 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps       : 100,
                //String - Animation easing effect
                animationEasing      : 'easeOutBounce',
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate        : true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale         : true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive           : true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio  : true,
                //String - A legend template
                legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                pieChart.Doughnut(PieData, pieOptions)
            }
                
            })

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
    })
        
    </script>

</body>



</html>