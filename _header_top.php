<?php
$activation_date = $_SESSION["activation_date"];
$user = $_SESSION["user"];

echo ' <header class="main-header">
            <a href="index.php" class="logo">  
                <span class="logo-mini"><b>BC</b></span>
                <span class="logo-lg"><b>Business Cards</b></span>
            </a>

            <nav class="navbar navbar-static-top" role="navigation">  
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs">Welcome, '.$user.'</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <p>'.$user.' 
                                        <small>Member since '.$activation_date.'</small>
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                    <a href="user_info.php" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                    <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>';

?>