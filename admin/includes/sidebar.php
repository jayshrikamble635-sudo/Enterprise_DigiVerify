<!-- DYNAMIC PREMIUM ICONIZED SIDEBAR LAYOUT -->
<div class="sidebar" style="position: fixed; top: 0; left: 0; height: 100vh; width: 260px; z-index: 99999 !important; pointer-events: auto !important; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); border-right: 1px solid rgba(255, 255, 255, 0.05); display: flex; flex-direction: column;">

    <!-- ================= LOGO ================= -->
    <div class="sidebar-logo" style="display: flex; align-items: center; gap: 14px; padding: 30px 20px;">
        <div style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 42px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; color: white; box-shadow: 0 4px 12px rgba(37,99,235,0.35);">
            🛡️
        </div>
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; margin: 0; font-family: 'Inter', sans-serif; line-height: 1;">DigiVerify</h2>
            <span style="font-size: 10px; color: #64748b; letter-spacing: 1px; font-weight: 700; text-transform: uppercase; margin-top: 3px; display: block;">ADMIN PANEL</span>
        </div>
    </div>

    <!-- ================= MENU TITLE ================= -->
    <div class="menu-title" style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; padding: 15px 20px 10px 20px; font-family: 'Inter', sans-serif;">
        MAIN MENU
    </div>

    <!-- ================= MENU LIST ================= -->
    <ul class="menu-list" style="list-style: none; padding: 0 15px; margin: 0; display: flex; flex-direction: column; gap: 8px;">
        <!-- Dashboard Option -->
        <li class="menu-item" style="width: 100%; display: block;">
            <a href="dashboard.php" style="display: flex; align-items: center; gap: 14px; color: #cbd5e1; text-decoration: none; padding: 13px 18px; border-radius: 12px; font-size: 15px; font-weight: 600; width: 100%; transition: 0.2s; font-family: 'Inter', sans-serif;">
                <span style="font-size: 18px; width: 24px; text-align: center;">📊</span>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Users Option -->
        <li class="menu-item" style="width: 100%; display: block;">
            <a href="users.php" style="display: flex; align-items: center; gap: 14px; color: #cbd5e1; text-decoration: none; padding: 13px 18px; border-radius: 12px; font-size: 15px; font-weight: 600; width: 100%; transition: 0.2s; font-family: 'Inter', sans-serif;">
                <span style="font-size: 18px; width: 24px; text-align: center;">👥</span>
                <span>Users</span>
            </a>
        </li>

        <!-- Documents Option -->
        <li class="menu-item" style="width: 100%; display: block;">
            <a href="documents.php" style="display: flex; align-items: center; gap: 14px; color: #cbd5e1; text-decoration: none; padding: 13px 18px; border-radius: 12px; font-size: 15px; font-weight: 600; width: 100%; transition: 0.2s; font-family: 'Inter', sans-serif; cursor: pointer !important;">
                <span style="font-size: 18px; width: 24px; text-align: center;">📄</span>
                <span>Documents</span>
            </a>
        </li>

        <!-- Verification History Option -->
        <li class="menu-item" style="width: 100%; display: block;">
            <a href="verification_history.php" style="display: flex; align-items: center; gap: 14px; color: #cbd5e1; text-decoration: none; padding: 13px 18px; border-radius: 12px; font-size: 15px; font-weight: 600; width: 100%; transition: 0.2s; font-family: 'Inter', sans-serif;">
                <span style="font-size: 18px; width: 24px; text-align: center;">🔍</span>
                <span>Verification History</span>
            </a>
        </li>
    </ul>

    <!-- ================= LOGOUT ================= -->
    <div class="logout" style="margin-top: auto; padding: 20px 15px;">
        <a href="logout.php" style="display: flex; align-items: center; gap: 14px; color: #ef4444; text-decoration: none; padding: 13px 18px; border-radius: 12px; font-size: 15px; font-weight: 600; width: 100%; transition: 0.2s; font-family: 'Inter', sans-serif;">
            <span style="font-size: 18px; width: 24px; text-align: center;">🚪</span>
            <span>Logout</span>
        </a>
    </div>

    <!-- ================= FOOTER ================= -->
    <div class="sidebar-footer" style="text-align: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 15px; margin: 0 15px 20px 15px;">
        <p style="font-size: 12px; color: #475569; margin: 0; font-family: 'Inter', sans-serif; font-weight: 500;">Enterprise DigiVerify</p>
        <p style="color:#2563eb; font-size:10.5px; font-weight:700; margin-top: 2px; font-family: 'Inter', sans-serif;">Live Sync Enabled</p>
    </div>

</div>

<!-- SMART HIGHLIGHTER & AUTOMATIC REDIRECT LOGIC -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let currentUrl = window.location.pathname.split("/").pop();
    if (currentUrl === "" || currentUrl === "index.php") {
        currentUrl = "dashboard.php";
    }
    
    let menuItems = document.querySelectorAll(".sidebar .menu-list .menu-item");
    menuItems.forEach(item => {
        let link = item.querySelector("a");
        if (link) {
            let hrefAttr = link.getAttribute("href");
            if (hrefAttr === currentUrl) {
                // Apply visual active theme style directly via inline override
                link.style.background = "linear-gradient(135deg, rgba(37, 99, 235, 0.18), rgba(6, 182, 212, 0.06))";
                link.style.color = "#38bdf8";
                link.style.borderLeft = "4px solid #2563eb";
                link.style.paddingLeft = "14px";
            }
        }

        // Direct box override listener for broken clicks
        item.addEventListener("click", function(e) {
            let innerLink = this.querySelector("a");
            if (innerLink) {
                window.location.href = innerLink.getAttribute("href");
            }
        });
    });
});
</script>
