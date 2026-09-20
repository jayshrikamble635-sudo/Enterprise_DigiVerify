<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Services | DigiVerify</title>

<!-- मुख्य होमपेज के फ़ॉन्ट्स और आइकॉन्स -->
<link href="https://googleapis.com" rel="stylesheet">
<link rel="stylesheet" href="https://cloudflare.com">
<link rel="stylesheet" href="css/style.css?v=<?php echo time();?>">

<style>
body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
    position: relative;
}

/* मुख्य प्रोफेशनल कंटेनर */
.services-container {
    background: rgba(15, 23, 42, 0.75) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 24px !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(37, 99, 235, 0.1) !important;
    max-width: 900px;
    width: 100%;
    padding: 50px 40px !important;
    backdrop-filter: blur(12px);
    text-align: center;
    z-index: 10;
}

/* मुख्य हेडिंग */
.services-container h1 {
    font-size: 38px !important;
    font-weight: 800 !important;
    letter-spacing: -1px;
    margin-bottom: 10px !important;
    background: linear-gradient(135deg, #ffffff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.services-container .subtitle {
    color: #94a3b8 !important;
    font-size: 15px;
    margin-bottom: 40px;
}

/* 3 Column Professional Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 45px;
}

/* सर्विस कार्ड डिज़ाइन */
.service-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 30px 20px;
    border-radius: 16px;
    text-align: left;
    transition: all 0.3s ease;
}

.service-card:hover {
    background: rgba(255, 255, 255, 0.04);
    border-color: rgba(6, 182, 212, 0.3);
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* निऑन ब्लू आइकॉन सर्कल */
.icon-wrapper {
    width: 50px;
    height: 50px;
    background: rgba(56, 189, 248, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.icon-wrapper i {
    font-size: 22px;
    color: #38bdf8;
}

.service-card h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
}

.service-card p {
    margin: 0;
    font-size: 13.5px;
    color: #94a3b8;
    line-height: 1.5;
}

/* बैक बटन */
.btn-back {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #2563eb, #06b6d4) !important;
    color: white !important;
    padding: 14px 35px !important;
    text-decoration: none !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4) !important;
    transition: all 0.3s ease;
}

.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(6, 182, 212, 0.6) !important;
}
</style>
</head>
<body>

<!-- मुख्य होमपेज के हूबहू बैकग्राउंड इफेक्ट्स -->
<div class="bg-grid"></div>
<div class="glow glow1"></div>
<div class="glow glow2"></div>
<div class="glow glow3"></div>

<!-- मुख्य कंटेनर बॉक्स -->
<div class="services-container">
    <h1>Our Core Services</h1>
    <p class="subtitle">Next-Generation Identity & Verification Capabilities</p>
    
    <!-- 3-Column ग्रिड लेआउट -->
    <div class="services-grid">
        
        <div class="service-card">
            <div class="icon-wrapper"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <h3>Secure Document Upload</h3>
            <p>Military-grade encrypted pipelines for uploading government and corporate identity assets safely.</p>
        </div>

        <div class="service-card">
            <div class="icon-wrapper"><i class="fa-solid fa-bolt-lightning"></i></div>
            <h3>Real-time Verification</h3>
            <p>Instantaneous AI-powered document structural scanning and global authority database checks.</p>
        </div>

        <div class="service-card">
            <div class="icon-wrapper"><i class="fa-solid fa-users-gear"></i></div>
            <h3>User Management</h3>
            <p>Advanced portal endpoints allowing individual tenants to audit and monitor their identity vault.</p>
        </div>

        <div class="service-card">
            <div class="icon-wrapper"><i class="fa-solid fa-sliders"></i></div>
            <h3>Admin Control Panel</h3>
            <p>High-level cryptographic oversight board designed for immediate manual approvals and node overrides.</p>
        </div>

        <div class="service-card">
            <div class="icon-wrapper"><i class="fa-solid fa-address-card"></i></div>
            <h3>Digital Identity Vault</h3>
            <p>Smart, responsive HTML-rendered portable legal certificates verified via isolated QR validation nodes.</p>
        </div>

    </div>
    
    <a href="index.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back Home
    </a>
</div>

</body>
</html>
