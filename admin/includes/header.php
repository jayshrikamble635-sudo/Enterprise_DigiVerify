<div class="topbar">

    <div class="top-left">

        <h1>📊 Admin Dashboard</h1>

        <p>Welcome back,
            <strong><?php echo $_SESSION['admin_name']; ?></strong>
        </p>

    </div>

    <div class="top-right">

        <div class="admin-profile">

            <div class="admin-avatar">
                👤
            </div>

            <div>

                <h4><?php echo $_SESSION['admin_name']; ?></h4>

                <span>Administrator</span>

            </div>

        </div>

        <a href="logout.php" class="logout-btn">
            🚪 Logout
        </a>

    </div>

</div>