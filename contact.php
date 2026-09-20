<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | DigiVerify</title>

<!-- आइकॉन और फ़ॉन्ट्स को सही करने वाले लिंक्स -->
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
    padding: 20px;
    position: relative;
}

.contact-container {
    background: rgba(15, 23, 42, 0.75) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 20px !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(37, 99, 235, 0.1) !important;
    max-width: 550px;
    width: 100%;
    padding: 45px 35px !important;
    backdrop-filter: blur(12px);
    text-align: center;
    z-index: 10;
}

.contact-container h1 {
    color: #ffffff !important;
    font-size: 32px !important;
    font-weight: 800 !important;
    margin-bottom: 10px !important;
}

.contact-container p {
    color: #94a3b8 !important;
    font-size: 15px !important;
    margin-bottom: 30px !important;
}

.info-list {
    margin-bottom: 30px;
}

.info-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 10px !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600 !important;
    color: #38bdf8 !important;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-value {
    color: #ffffff !important;
    font-weight: 500;
    font-size: 15px;
}

.btn-back {
    display: inline-block !important;
    background: linear-gradient(135deg, #2563eb, #06b6d4) !important;
    color: white !important;
    padding: 12px 35px !important;
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

<div class="contact-container">
    <h1>Contact Support</h1>
    <p>Enterprise DigiVerify AI Helpdesk System</p>
    
    <div class="info-list">
        <div class="info-item">
            <span class="info-label"><i class="fa-solid fa-envelope"></i> Email:</span>
            <span class="info-value">support@digiverify.com</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fa-solid fa-globe"></i> Website:</span>
            <span class="info-value">://digiverify.com</span>
        </div>
        <div class="info-item">
            <span class="info-label"><i class="fa-solid fa-shield-halved"></i> Security:</span>
            <span class="info-value">SHA-512 Encrypted Node</span>
        </div>
    </div>
    
    <a href="index.php" class="btn-back">
        Back Home
    </a>
</div>

</body>
</html>
