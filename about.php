<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | DigiVerify</title>

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
    padding: 40px 20px;
    position: relative;
}

/* मुख्य प्रोफेशनल कार्ड कंटेनर */
.about-container {
    background: rgba(15, 23, 42, 0.75) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 24px !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(37, 99, 235, 0.1) !important;
    max-width: 750px;
    width: 100%;
    padding: 50px 40px !important;
    backdrop-filter: blur(12px);
    text-align: center;
    z-index: 10;
}

/* हेडिंग - ग्रेडिएंट इफ़ेक्ट के साथ */
.about-container h1 {
    font-size: 36px !important;
    font-weight: 800 !important;
    letter-spacing: -1px;
    margin-bottom: 15px !important;
    background: linear-gradient(135deg, #ffffff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.about-container h1 span {
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* मुख्य विवरण (Description) */
.about-desc {
    color: #cbd5e1 !important;
    font-size: 16px !important;
    line-height: 1.7 !important;
    margin-bottom: 35px !important;
}

/* फीचर्स ग्रिड जो इसे प्रोफेशनल बनाएगा */
.features-mini-grid {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.feature-box {
    flex: 1;
    min-width: 200px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 12px;
    transition: 0.3s;
}

.feature-box:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(56, 189, 248, 0.2);
}

.feature-box i {
    font-size: 24px;
    color: #38bdf8;
    margin-bottom: 12px;
}

.feature-box h3 {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
}

.feature-box p {
    margin: 0;
    font-size: 13px;
    color: #94a3b8;
    line-height: 1.4;
}

/* प्रोफेशनल बैक बटन */
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

<!-- मुख्य कार्ड -->
<div class="about-container">
    <h1>About <span>DigiVerify</span></h1>
    
    <p class="about-desc">
        DigiVerify is an enterprise-level digital identity and document verification system. 
        It helps organizations securely manage user identities and verify documents in real-time 
        using next-generation AI architecture.
    </p>

    <!-- यह ग्रिड आपके साधारण पेज को बेहद प्रोफेशनल लुक देगा -->
    <div class="features-mini-grid">
        <div class="feature-box">
            <i class="fa-solid fa-bolt"></i>
            <h3>Real-Time AI</h3>
            <p>Instant document validation and extraction.</p>
        </div>
        <div class="feature-box">
            <i class="fa-solid fa-lock"></i>
            <h3>Secure Vault</h3>
            <p>Military-grade encrypted data processing nodes.</p>
        </div>
        <div class="feature-box">
            <i class="fa-solid fa-chart-pie"></i>
            <h3>Compliance</h3>
            <p>Automated global verification standards mapping.</p>
        </div>
    </div>
    
    <a href="index.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back Home
    </a>
</div>

</body>
</html>
