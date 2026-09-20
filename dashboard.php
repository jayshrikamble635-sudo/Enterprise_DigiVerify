<?php
session_start();
include("../database/config.php");

// अगर Login System है तो इसे रहने दो
/*
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
*/

// ===== Dummy Counts =====
// बाद में इन्हें Database Query से बदल देंगे
$totalUsers = 1250;
$totalAdmins = 5;
$normalUsers = 1245;
$todayUsers = 18;

$totalDocuments = 3480;
$approvedDocuments = 3310;
$pendingDocuments = 170;
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Enterprise DigiVerify | Admin Dashboard</title>

<link rel="stylesheet" href="css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>
<body>

<?php include("include/sidebar.php"); ?>


<div class="main-content">

    <!-- HEADER -->

    <header class="top-header">

        <div>

            <h1>Admin Dashboard</h1>

            <p>Welcome back, Administrator</p>

        </div>

        <div class="header-right">

            <div class="admin-profile">

                <div class="profile-icon">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <div>

                    <h4>Admin</h4>

                    <span>Administrator</span>

                </div>

            </div>

            <a href="logout.php" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>

        </div>

    </header>


    <!-- STATISTICS -->

    <section class="stats-grid">

        <div class="card">

            <span>Total Users</span>

            <h2><?php echo $totalUsers; ?></h2>

            <i class="fa-solid fa-users"></i>

        </div>

        <div class="card">

            <span>Admins</span>

            <h2><?php echo $totalAdmins; ?></h2>

            <i class="fa-solid fa-user-shield"></i>

        </div>

        <div class="card glow-card">

            <span>Users</span>

            <h2><?php echo $normalUsers; ?></h2>

            <i class="fa-solid fa-user-group"></i>

        </div>

        <div class="card">

            <span>Today's Users</span>

            <h2><?php echo $todayUsers; ?></h2>

            <i class="fa-solid fa-user-plus"></i>

        </div>

    </section>



    <!-- HERO SECTION -->

    <section class="hero-box">

        <div class="hero-left">

            <span class="badge">
                Enterprise DigiVerify
            </span>

            <h2>
                AI Powered Admin Dashboard
            </h2>

            <p>

                Monitor document verification,
                manage users,
                track approvals,
                and control your enterprise
                platform from one powerful dashboard.

            </p>

        </div>


        <div class="hero-right">

            <div class="mini-card">

                <span>Total Documents</span>

                <h3><?php echo $totalDocuments; ?></h3>

            </div>

            <div class="mini-card">

                <span>Approved</span>

                <h3><?php echo $approvedDocuments; ?></h3>

            </div>

            <div class="mini-card">

                <span>Pending</span>

                <h3><?php echo $pendingDocuments; ?></h3>

            </div>

        </div>

    </section>
        <!-- =========================
         RECENT DOCUMENTS
    ========================== -->

    <div class="dashboard-row">

        <div class="table-card">

            <div class="table-header">
                <h3>
                    <i class="fa-solid fa-file-shield"></i>
                    Recent Documents
                </h3>

                <a href="documents.php" class="view-btn">
                    View All
                </a>
            </div>

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>User</th>

                        <th>Document</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>#101</td>

                        <td>Riddhi</td>

                        <td>Aadhar Card</td>

                        <td>
                            <span class="status approved">
                                Approved
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>#102</td>

                        <td>Rahul</td>

                        <td>PAN Card</td>

                        <td>
                            <span class="status pending">
                                Pending
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>#103</td>

                        <td>Priya</td>

                        <td>Passport</td>

                        <td>
                            <span class="status approved">
                                Approved
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>#104</td>

                        <td>Amit</td>

                        <td>Driving Licence</td>

                        <td>
                            <span class="status rejected">
                                Rejected
                            </span>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>





        <div class="table-card">

            <div class="table-header">

                <h3>

                    <i class="fa-solid fa-users"></i>

                    Recent Users

                </h3>

                <a href="users.php" class="view-btn">

                    View All

                </a>

            </div>

            <table>

                <thead>

                    <tr>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Role</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>Riddhi</td>

                        <td>riddhi@gmail.com</td>

                        <td>User</td>

                    </tr>

                    <tr>

                        <td>Rahul</td>

                        <td>rahul@gmail.com</td>

                        <td>User</td>

                    </tr>

                    <tr>

                        <td>Admin</td>

                        <td>admin@gmail.com</td>

                        <td>Administrator</td>

                    </tr>

                    <tr>

                        <td>Priya</td>

                        <td>priya@gmail.com</td>

                        <td>User</td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>




    <!-- ======================
         FOOTER
    ======================= -->

    <footer class="dashboard-footer">

        © <?php echo date("Y"); ?>

        Enterprise DigiVerify |

        AI Powered Admin Dashboard

    </footer>

</div>

</body>
</html>