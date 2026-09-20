<?php
session_start();
include("../database/config.php");

// यदि सब-एडमिन लॉग इन नहीं है तो उसे लॉगिन पेज पर भेजें
if(!isset($_SESSION['subadmin_id'])){
    header("Location: login.php");
    exit();
}

// वर्तमान में लॉग इन सब-एडमिन की ID निकालना
$subadmin_id = intval($_SESSION['subadmin_id']);

// डेटाबेस की 'subadmins' टेबल से विशिष्ट सब-एडमिन का डेटा निकालना
$query = "SELECT id, fullname, email, created_at FROM subadmins WHERE id = $subadmin_id LIMIT 1";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0) {
    $admin_data = mysqli_fetch_assoc($result);
} else {
    // अगर डेटाबेस में आईडी न मिले तो फॉलबैक डेटा
    $admin_data = [
        'fullname' => 'Sub Administrator',
        'email' => 'subadmin@digiverify.com',
        'created_at' => date('Y-m-d H:i:s')
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>My Profile | DigiVerify Sub Admin</title>
    <link rel="stylesheet" href="../admin/css/admin.css">
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        /* प्रोफाइल व्यू विशेष स्टाइल */
        .profile-container {
            background: #0b1528;
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        .profile-header-card {
            display: flex;
            align-items: center;
            gap: 25px;
            border-bottom: 1px solid #1e293b;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        .profile-avatar {
            background: #1e293b;
            color: #3b82f6;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #3b82f6;
        }
        .profile-title-block h2 { margin: 0; font-size: 22px; color: #fff; }
        .profile-title-block p { margin: 5px 0 0 0; color: #64748b; font-size: 14px; }
        
        .info-grid { display: flex; flex-direction: column; gap: 20px; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .info-label { color: #94a3b8; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .info-value { color: #fff; font-size: 15px; font-weight: bold; }
    </style>
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">
    <div class="top-header">
        <div class="header-left">
            <h1>Sub Admin Panel</h1>
            <p>My Profile &bull; Sub Administrator Details</p>
        </div>
        <div class="header-right">
            <div class="admin-profile">
                <div class="profile-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4>Sub Admin</h4>
                    <span>Enterprise DigiVerify</span>
                </div>
            </div>
        </div>
    </div>
    <!-- PROFILE BOX MODULE -->
    <div class="table-card" style="padding: 20px;">
        <div class="table-header" style="margin-bottom: 20px;">
            <h3>Profile Information</h3>
        </div>

        <div class="profile-container">
            <div class="profile-header-card">
                <div class="profile-avatar">
                    <i class="fas fa-user-gear fa-3x"></i>
                </div>
                <div class="profile-title-block">
                    <h2><?php echo htmlspecialchars($admin_data['fullname']); ?></h2>
                    <p>Sub Administrator &bull; Enterprise Security Portal</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-fingerprint" style="color: #3b82f6;"></i> Account ID</span>
                    <span class="info-value">#<?php echo $subadmin_id; ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-signature" style="color: #3b82f6;"></i> Full Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($admin_data['fullname']); ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-envelope" style="color: #3b82f6;"></i> Email Address</span>
                    <span class="info-value"><?php echo htmlspecialchars($admin_data['email']); ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-building-shield" style="color: #3b82f6;"></i> Organization Unit</span>
                    <span class="info-value" style="color: #3b82f6;">Enterprise DigiVerify</span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar-alt" style="color: #3b82f6;"></i> Created At</span>
                    <span class="info-value"><?php echo date("Y-m-d H:i", strtotime($admin_data['created_at'])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-footer">
        <p>
            © <?php echo date("Y"); ?> Enterprise DigiVerify | Profile Panel | Developed by Riddhi Kamble
        </p>
    </div>
</div>

</body>
</html>
