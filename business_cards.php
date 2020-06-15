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
    <link href="https://fonts.googleapis.com/css?family=Arimo|Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="cards.css">
    <title>Business Cards - Cards</title>   
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
                My Cards
                <small>View your business cards</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i>Home</a></li>
                <li><a href="#"></i>Business Cards</a></li>
                <li class="active">My Cards</li>
            </ol>
        </section>

        <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        --------------------------> 
    <div class='row'>
        <div class='col-md-2'></div>
		<div class="col-md-8">
		<div class="box box-primary">
			<div class="box-body">
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
            <!--               -->                
			
			</div>
		</div>
		</div>
		<div class='col-md-2'></div>
	</div>
	<div class='row'>
        <div class='col-md-5'></div>
		<div class='col-md-1'>
            <i class='fa fa-plus fa-2x' title='Add Card' onclick="window.location.href='business_card_new.php'"></i>
        </div>
        <div class='col-md-1'>
		    <i id="card-modify" title='Update Card' class='fa fa-pencil-square fa-2x' type="submit"></i>
        </div>
        <div class='col-md-4'>
            <i id="card-remove" title='Delete Card' class='fa fa-remove fa-2x' data-toggle='modal'></i>
        </div>  
	</div><!--Row-->  
    <form id="update-card" action="business_cards_modify.php" method="POST">
		<input type="hidden" id="card_id" name="card_id">
	</form>
     
<!--MODALS-->
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
        var card_id;
        $(document).ready(function() {
            $('.radioButton').on('click', function() {
                $(".radioButton").prop('checked', false);
                $(this).prop('checked', true);
                card_id = $(this).attr("data-card_id");
            });

            $('#card-modify').on( 'click', function () {
                if(card_id != undefined){
                    $('#card_id').val(card_id);
                    $("#update-card").submit();
                }
                else
                    message("Select a card to update");  
            });

            $('#card-remove').on( 'click', function () {
                if(card_id != undefined){
                    $('#card_id').val(card_id);
                    $("#card-remove").attr("data-target","#modal-delete");
                }
                else
                    message("Select a card to delete");  
            });
            
            $('#confirm-delete').on('click', function () {
                $.ajax({
                        type: "POST",
                        url: "/homework/business_card_delete.php",
                        data: {card_id: card_id},
                        dataType: "html",
                        success: function(msg)
                        {
                            message(msg);
                            setInterval(function(){
                                location.reload();
                            },2000)
                                               
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
        });

    </script>
    
</body>
</html>