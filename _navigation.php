<?php
echo '<aside class="main-sidebar">
<section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION BAR</li>

            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="account_info.php"><i class="fa fa-info"></i> <span>Account Info</span></a></li>
            <li><a href="user_info.php"><i class="fa fa-user"></i> <span>Your Profile</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-id-card"></i> <span>Business Cards</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="business_cards.php">My Cards</a></li>
                    <li><a href="business_cards_all.php">All Cards</a></li>
                </ul>
            </li>
            <li><a href="meetings.php"><i class="fa fa-calendar"></i> <span>Meetings</span></a></li>
    </ul>

</section>
</aside>';

?>