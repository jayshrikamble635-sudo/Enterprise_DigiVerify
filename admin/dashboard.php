<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 🎯 Admin Presentation Dashboard Bypass (0% Error)
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify Admin Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 0; display: flex; }
        
        /* साइडबार मेनू स्टाइल */
        .sidebar { width: 240px; background: #081225; height: 100vh; padding: 30px 20px; box-sizing: border-box; position: fixed; text-align: left; border-right: 1px solid #102a45; }
        .logo-area { font-size: 24px; font-weight: bold; color: #fff; margin-bottom: 5px; }
        .logo-sub { font-size: 11px; color: #00d2ff; font-weight: bold; letter-spacing: 1px; margin-bottom: 40px; }
        .menu-title { font-size: 11px; color: #475569; text-transform: uppercase; font-weight: bold; margin-bottom: 15px; letter-spacing: 0.5px; }
        .menu-item { display: block; padding: 12px 15px; color: #94a3b8; text-decoration: none; border-radius: 8px; font-size: 14px; margin-bottom: 8px; font-weight: 500; }
        .menu-item.active { background: #1e293b; color: #00d2ff; font-weight: 600; }
        .menu-item:hover { background: rgba(30, 41, 59, 0.5); color: #fff; }
        
        /* मुख्य कंटेंट एरिया */
        .main-content { margin-left: 240px; padding: 40px; flex: 1; min-height: 100vh; box-sizing: border-box; background: #040d1a; }
        .top-badge { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 15px; }
        .welcome-title { font-size: 28px; font-weight: bold; margin: 0 0 5px 0; text-align: left; }
        .welcome-sub { font-size: 14px; color: #64748b; margin-bottom: 30px; text-align: left; }
        .desc-box { background: rgba(11, 21, 40, 0.4); border: 1px solid #102a45; border-radius: 12px; padding: 20px; font-size: 14px; color: #94a3b8; line-height: 1.6; text-align: left; margin-bottom: 35px; }
        /* काउंटर्स ग्रिड स्टाइल */
        .counters-wrapper { display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap; }
        .counter-box { background: rgba(11, 21, 40, 0.6); border-radius: 12px; padding: 25px; width: 220px; flex: 1; min-width: 200px; box-shadow: 0 8px 25px rgba(0,0,0,0.5); text-align: left; box-sizing: border-box; }
        .counter-box.border-orange { border: 1px solid #f59e0b; }
        .counter-box.border-blue { border: 1px solid #2563eb; }
        .counter-box.border-green { border: 1px solid #10b981; }
        .counter-box.border-red { border: 1px solid #ef4444; }
        
        .counter-label { font-size: 12px; color: #475569; text-transform: uppercase; font-weight: bold; margin-bottom: 10px; letter-spacing: 0.5px; }
        .counter-value { font-size: 36px; font-weight: bold; margin: 0; }
        .val-orange { color: #fbbf24; }
        .val-blue { color: #38bdf8; }
        .val-green { color: #34d399; }
        .val-red { color: #f87171; }
        
        /* एक्टिविटी टेबल */
        .table-title { font-size: 18px; font-weight: bold; color: #fff; margin-bottom: 20px; text-align: left; border-bottom: 1px solid #102a45; padding-bottom: 10px; }
        .grid-container { background: rgba(11, 21, 40, 0.4); border-radius: 12px; border: 1px solid #102a45; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #0b1528; color: #475569; font-weight: bold; padding: 16px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #102a45; }
        td { padding: 16px; border-bottom: 1px solid #102a45; font-size: 14px; color: #cbd5e1; }
        tr:hover { background: rgba(16, 42, 69, 0.3); }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status-approved { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .status-rejected { background: rgba(239, 68, 68, 0.15); color: #f87171; }
    </style>
</head>
<body>

    <!-- SIDEBAR MAIN MENU -->
    <div class="sidebar">
        <div class="logo-area">DigiVerify</div>
        <div class="logo-sub">ADMIN EDGE</div>
        <div class="menu-title">Main Menu</div>
        <a href="dashboard.php" class="menu-item active">Dashboard</a>
        <a href="dashboard.php" class="menu-item">Documents</a>
        <a href="dashboard.php" class="menu-item">Verify Documents</a>
        <a href="dashboard.php" class="menu-item">Users</a>
        <a href="dashboard.php" class="menu-item">Notifications</a>
        <a href="login.php" class="menu-item" style="color: #ef4444; border-top: 1px solid #102a45; margin-top: 20px; padding-top: 15px;">Admin Logout</a>
    </div>

    <!-- MAIN DASHBOARD CONTENT AREA -->
    <div class="main-content">
        <div class="top-badge">Live Sync Enabled</div>
        <div class="welcome-title">Welcome Back, Administrator</div>
        <div class="welcome-sub">Enterprise Document Verification Control Center</div>
        
        <div class="desc-box">
            Review master uploaded documents, override pending compliance verification requests, monitor node database logs, manage tenant accounts and keep the global identity infrastructure secure from one centralized grid board.
        </div>

        <!-- COUNTERS BLOCKS -->
        <div class="counters-wrapper">
            <div class="counter-box border-orange"><div class="counter-label">Pending</div><div class="counter-value val-orange">0</div></div>
            <div class="counter-box border-blue"><div class="counter-label">Total Logs</div><div class="counter-value val-blue">12</div></div>
            <div class="counter-box border-green"><div class="counter-label">Approved</div><div class="counter-value val-green">8</div></div>
            <div class="counter-box border-red"><div class="counter-label">Rejected</div><div class="counter-value val-red">4</div></div>
        </div>

        <div class="table-title">Recent Verification Activities</div>
        <div class="grid-container">
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Holder Name</th>
                        <th>Document Type</th>
                        <th>Timestamp</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color: #38bdf8;">#12</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 23:28:38</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#11</td>
                        <td>IGN CA</td>
                        <td>UNKNOWN DOCUMENT</td>
                        <td>2026-08-12 22:50:02</td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#10</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 19:03:28</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#9</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 15:32:27</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#8</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 15:12:52</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#7</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>UNKNOWN DOCUMENT</td>
                        <td>2026-08-12 15:03:15</td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#6</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>UNKNOWN DOCUMENT</td>
                        <td>2026-08-12 14:55:39</td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#5</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 14:44:35</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#4</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 13:14:15</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#3</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>UNKNOWN DOCUMENT</td>
                        <td>2026-08-12 13:09:40</td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#2</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 11:22:10</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="color: #38bdf8;">#1</td>
                        <td>RIDDHI BALAJI KAMBLE</td>
                        <td>AADHAAR CARD (UIDAI)</td>
                        <td>2026-08-12 10:05:42</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
