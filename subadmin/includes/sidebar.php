<?php

$current = basename($_SERVER['PHP_SELF']);

?>

<aside class="sidebar">

    <!-- LOGO -->
    <div class="logo-area">

        <div class="logo-circle">
            <i class="fas fa-shield-halved"></i>
        </div>

        <div class="logo-text">

            <h2>DigiVerify</h2>

            <span>SUB ADMIN PANEL</span>

        </div>

    </div>


    <!-- MENU TITLE -->
    <div class="menu-title">
        MAIN MENU
    </div>


    <!-- MENU -->
    <ul class="menu">

        <li class="<?=($current=="dashboard.php")?'active':'';?>">

            <a href="dashboard.php">

                <i class="fas fa-gauge"></i>

                <span>Dashboard</span>

            </a>

        </li>


        <li class="<?=($current=="documents.php")?'active':'';?>">

            <a href="documents.php">

                <i class="fas fa-file-lines"></i>

                <span>Documents</span>

            </a>

        </li>


        <li class="<?=($current=="verify.php")?'active':'';?>">

            <a href="verify.php">

                <i class="fas fa-circle-check"></i>

                <span>Verify Documents</span>

            </a>

        </li>


        <li class="<?=($current=="users.php")?'active':'';?>">

            <a href="users.php">

                <i class="fas fa-users"></i>

                <span>Users</span>

            </a>

        </li>


        <li class="<?=($current=="notifications.php")?'active':'';?>">

            <a href="notifications.php">

                <i class="fas fa-bell"></i>

                <span>Notifications</span>

            </a>

        </li>


        <li class="<?=($current=="profile.php")?'active':'';?>">

            <a href="profile.php">

                <i class="fas fa-user"></i>

                <span>Profile</span>

            </a>

        </li>


        <li>

            <a href="logout.php" class="logout-link">

                <i class="fas fa-right-from-bracket"></i>

                <span>Logout</span>

            </a>

        </li>

    </ul>


    <!-- SIDEBAR FOOTER -->
    <div class="sidebar-footer">

        <p>Enterprise DigiVerify</p>

        <small>Sub Admin Panel</small>

    </div>

</aside>