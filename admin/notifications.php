<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../database/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// सुरक्षा जांच: अगर एडमिन लॉगिन नहीं है, तो लॉगिन पेज पर भेजें
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

// सिस्टम नोटिफिकेशन की डमी या लाइव लिस्ट सेट करना (आपकी टेबल के अनुसार)
// अगर आपके पास notifications नाम की टेबल है, तो आप नीचे वाली क्वेरी अनकमेंट कर सकते हैं:
// $notifications_query = mysqli_query($conn, "SELECT * FROM notifications ORDER BY id DESC LIMIT 20");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Notifications | DigiVerify Admin</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #0b0f19; color: #f8fafc; display: flex; min-height: 100vh; overflow-x: hidden; }

        /* LEFT SIDEBAR DESIGN */
        .sidebar {
            width: 260px; background: rgba(15, 23, 42, 0.9); border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px 20px; display: flex; flex-direction: column; backdrop-filter: blur(10px);
            position: fixed; height: 100vh; z-index: 100; left: 0; top: 0;
        }
        .sidebar-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; }
        .sidebar-logo h2 { font-size: 22px; font-weight: 800; color: #ffffff; }
        .sidebar-logo p { font-size: 11px; color: #38bdf8; text-transform: uppercase; font-weight: 600; margin-top: 2px; }
        .menu-label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; margin-bottom: 15px; }
        .menu-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .menu-item a {
            display: flex; align-items: center; gap: 14px; color: #cbd5e1; text-decoration: none;
            padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 500; transition: 0.2s;
        }
        .menu-item.active a, .menu-item a:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(6, 182, 212, 0.05));
            color: #38bdf8; border-left: 3px solid #2563eb; padding-left: 13px;
        }
        .sidebar-footer { margin-top: auto; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px; }
        .sidebar-footer p { font-size: 11px; color: #475569; }

        /* RIGHT MAIN CONTENT AREA */
        .main-content {
            margin-left: 260px; flex: 1; width: calc(100% - 260px); padding: 40px;
            position: relative; z-index: 1; display: block; background-color: #0b0f19; min-height: 100vh;
        }
        .top-header {
            background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 25px 30px; border-radius: 20px; display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 30px; backdrop-filter: blur(10px);
        }
        .top-header h1 { font-size: 26px; font-weight: 800; color: #ffffff; }
        .top-header p { color: #94a3b8; font-size: 13.5px; margin-top: 3px; }

        /* नोटिफिकेशन लिस्ट कार्ड्स */
        .notification-card {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px 25px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.2s;
        }
        .notification-card:hover {
            background: rgba(15, 23, 42, 0.7);
            border-color: rgba(56, 189, 248, 0.2);
        }
        .notif-left { display: flex; align-items: center; gap: 20px; }
        .notif-icon {
            width: 44px; height: 44px; background: rgba(56, 189, 248, 0.1); 
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
        }
        .notif-info h3 { font-size: 16px; color: #ffffff; font-weight: 600; }
        .notif-info p { font-size: 14px; color: #cbd5e1; margin-top: 4px; }
        .notif-date { color: #64748b; font-size: 13px; font-weight: 500; text-align: right; }

        .dashboard-footer { border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px; display: flex; justify-content: space-between; color: #475569; font-size: 13px; }
    </style>
</head>
<body>

    <!-- ============================================================
         बायाँ नेविगेशन साइडबार (LEFT SIDEBAR - DIRECT SVG ICONS)
         ============================================================ -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://w3.org" style="filter: drop-shadow(0 0 8px rgba(37, 99, 235, 0.6));">
                <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="#2563eb" stroke="#38bdf8" stroke-width="2" stroke-linejoin="round"/>
                <path d="M12 6V18" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                <path d="M9 11L12 14L15 11" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
                <h2>DigiVerify</h2>
                <p>Admin Edge</p>
            </div>
        </div>

        <p class="menu-label">Main Menu</p>
        <ul class="menu-list">
            <li class="menu-item">
                <a href="dashboard.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                    Dashboard
                </a>
            </li>
            <li class="menu-item">
                <a href="manage-documents.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Documents
                </a>
            </li>
            <li class="menu-item">
                <a href="verify-requests.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Verify Documents
                </a>
            </li>
            <li class="menu-item">
                <a href="manage-users.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Users
                </a>
            </li>
            <li class="menu-item active">
                <a href="notifications.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    Notifications
                </a>
            </li>
            <li class="menu-item">
                <a href="profile.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Profile
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <p>Enterprise DigiVerify</p>
            <p style="color: #38bdf8; font-size: 10px; margin-top: 4px;">Developed by Riddhi Kamble</p>
        </div>
    </div>

    <!-- ============================================================
         दायाँ मुख्य भाग (RIGHT CONTENT PANEL)
         ============================================================ -->
    <div class="main-content">

        <div class="top-header">
            <div>
                <h1>Notifications</h1>
                <p>Latest Core Security System Activity Audits</p>
            </div>
            <div class="admin-profile" style="display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                <span>Administrator</span>
            </div>
        </div>

        <!-- सुव्यवस्थित कस्टमाइज्ड नोटिफिकेशन लिस्ट कार्ड्स -->
        <div class="notification-list-wrapper">

            <!-- NOTIF 1 -->
            <div class="notification-card">
                <div class="notif-left">
                    <div class="notif-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                    </div>
                    <div class="notif-info">
                        <h3>New User Registration</h3>
                        <p>A new tenant user account has registered and initialized handshake node successfully.</p>
                    </div>
                </div>
                <div class="notif-date">12 Aug 2026 16:03</div>
            </div>

            <!-- NOTIF 2 -->
            <div class="notification-card">
                <div class="notif-left">
                    <div class="notif-icon" style="background: rgba(245, 158, 11, 0.1);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div class="notif-info">
                        <h3>Document Uploaded</h3>
                        <p>A new identity credential asset is queued and waiting for structural AI verification.</p>
                    </div>
                </div>
                <div class="notif-date">12 Aug 2026 16:03</div>
            </div>

            <!-- NOTIF 3 -->
            <div class="notification-card">
                <div class="notif-left">
                    <div class="notif-icon" style="background: rgba(16, 185, 129, 0.1);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="notif-info">
                        <h3>Verification Completed</h3>
                        <p>Document structural analysis matches master node database. Approved successfully.</p>
                    </div>
                </div>
                <div class="notif-date">12 Aug 2026 16:03</div>
            </div>

            <!-- NOTIF 4 -->
            <div class="notification-card">
                <div class="notif-left">
                    <div class="notif-icon" style="background: rgba(245, 158, 11, 0.1);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <div class="notif-info">
                        <h3>Document Uploaded</h3>
                        <p>Your secure framework identity log document asset has been processed and saved.</p>
                    </div>
                </div>
                <div class="notif-date">08 Aug 2026 08:49</div>
            </div>

        </div>

        <div class="dashboard-footer">
            <p>Enterprise DigiVerify | <span>AI Powered Document Verification System</span></p>
            <p>© <?= date("Y"); ?> All Rights Reserved</p>
        </div>

    </div> <!-- main-content close -->

</body>
</html>
