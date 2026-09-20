<?php
error_reporting(0);
ini_set('display_errors',0);

/* ===========================
   PLATFORM STATS
=========================== */

$stats_data = [

    ["10,000+","Registered Users","fa-user-astronaut"],
    ["50,000+","Documents Verified","fa-fingerprint"],
    ["99.9%","Verification Accuracy","fa-shield-halved"],
    ["100+","Enterprise Clients","fa-building-shield"]

];

/* ===========================
   FEATURES
=========================== */

$features_data = [

[
"fa-shield-halved",
"AI Cryptography",
"Military-grade encryption protecting every identity and document."
],

[
"fa-bolt",
"Quantum Verification",
"Real-time AI powered identity verification with instant validation."
],

[
"fa-chart-line",
"Compliance Analytics",
"Enterprise monitoring with fraud detection and audit reports."
],

[
"fa-user-shield",
"Biometric Security",
"Multi-layer biometric authentication with secure access control."
]

];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>DigiVerify | Enterprise Digital Identity Platform</title>

<link rel="preconnect"
href="https://fonts.googleapis.com">

<link rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="stylesheet"
href="css/style.css?v=<?php echo time();?>">

</head>

<body>

<!-- ===========================
BACKGROUND EFFECTS
=========================== -->

<div class="bg-grid"></div>

<div class="glow glow1"></div>

<div class="glow glow2"></div>

<div class="glow glow3"></div>

<!-- ===========================
NAVBAR
=========================== -->

<header class="navbar">

<div class="container nav-container">

<div class="logo">

<div class="logo-icon">

<i class="fa-solid fa-shield-halved"></i>

</div>

<div>

<h2>DigiVerify</h2>

<p>Digital Identity Security</p>

</div>

</div>

<nav>
<ul class="nav-menu">

<li><a href="index.php">Home</a></li>

<li><a href="services.php">Features</a></li>

<li><a href="about.php">Analytics</a></li>

<li><!-- इसे उसकी जगह लगा दें -->
<a href="javascript:void(0)" onclick="loadContactPage()">Contact</a>
</li>

</ul>
</nav>



<div class="nav-buttons">

<a href="verify/choose-document.php"
class="btn-primary">

<i class="fa-solid fa-rocket"></i>

Get Started

</a>

<a href="user/login.php"
class="btn-secondary">

<i class="fa-solid fa-user"></i>

User Portal

</a>

<a href="admin/login.php"
class="btn-secondary">

<i class="fa-solid fa-lock"></i>

Admin Edge

</a>

<a href="subadmin/login.php"
class="btn-secondary">

<i class="fa-solid fa-users"></i>

Sub Admin

</a>

</div>

</div>

</header>

<!-- ===========================
HERO
=========================== -->

<section class="hero">

<div class="container hero-container">

<!-- LEFT -->

<div class="hero-left">

<div class="hero-badge">

<i class="fa-solid fa-sparkles"></i>

Next-Gen AI Verification Engine

</div>

<h1>

Enterprise Identity

<br>

<span>Document Verification</span>

</h1>

<p>

Secure digital identity with enterprise-grade AI verification,
biometric authentication, encrypted document validation
and fraud detection.

Designed for Government, Universities,
Banking and Enterprise organizations.

</p>

<ul class="hero-list">

<li>
<i class="fa-solid fa-circle-check"></i>
Live Multi-Tenant Security Vaults
</li>

<li>
<i class="fa-solid fa-circle-check"></i>
Quantum-Safe Document Authentication
</li>

<li>
<i class="fa-solid fa-circle-check"></i>
Automated Global Compliance Mapping
</li>

<li>
<i class="fa-solid fa-circle-check"></i>
AI Fraud Detection Engine
</li>

</ul>

<div class="hero-buttons">

<a href="verify/choose-document.php"
class="hero-btn hero-btn-primary">

<i class="fa-solid fa-fingerprint"></i>

Start Verification

</a>

<a href="admin/login.php"
class="hero-btn hero-btn-outline">

<i class="fa-solid fa-shield-halved"></i>

Admin Command

</a>

</div>

<div class="hero-stats">

<div>

<h3>50K+</h3>

<span>Verified Documents</span>

</div>

<div>

<h3>99.9%</h3>

<span>Accuracy</span>

</div>

<div>

<h3>24×7</h3>

<span>Monitoring</span>

</div>

</div>

</div>

<!-- RIGHT START -->
<div class="hero-right">

<div class="dashboard-card">

<div class="dashboard-header">

<h3>

<i class="fa-solid fa-chart-line"></i>

Live Verification Analytics

</h3>

<span class="status-online">

● ONLINE

</span>

</div>
<!-- ===========================
LIVE STATS
=========================== -->

<div class="dashboard-stats">

<div class="dash-item">
<i class="fa-solid fa-file-shield"></i>

<div>
<h4>50K+</h4>
<p>Documents Verified</p>
</div>

</div>

<div class="dash-item">
<i class="fa-solid fa-lock"></i>

<div>
<h4>100%</h4>
<p>System Secure</p>
</div>

</div>

<div class="dash-item">
<i class="fa-solid fa-heart-pulse"></i>

<div>
<h4>Operational</h4>
<p>Node Health</p>
</div>

</div>

<div class="dash-item">
<i class="fa-solid fa-key"></i>

<div>
<h4>SHA-512</h4>
<p>Encryption</p>
</div>

</div>

<div class="dash-item">
<i class="fa-solid fa-bullseye"></i>

<div>
<h4>99.9%</h4>
<p>Accuracy Index</p>
</div>

</div>

</div>


<!-- ===========================
LIVE QUERY GRAPH
=========================== -->

<div class="graph-card">

<h4>

<i class="fa-solid fa-chart-column"></i>

Live Query Load

</h4>

<div class="graph-bars">

<div class="bar" style="height:45%"></div>

<div class="bar active" style="height:80%"></div>

<div class="bar" style="height:55%"></div>

<div class="bar active" style="height:95%"></div>

<div class="bar" style="height:70%"></div>

<div class="bar" style="height:60%"></div>

<div class="bar active" style="height:88%"></div>

<div class="bar" style="height:50%"></div>

</div>

</div>


<!-- ===========================
ACCURACY CIRCLE
=========================== -->

<div class="accuracy-box">

<div class="circle">

<h2>99.9%</h2>

<span>Accuracy</span>

</div>

<div class="accuracy-text">

<h3>Enterprise AI Engine</h3>

<p>

Government-grade verification with
real-time biometric authentication
and fraud detection.

</p>

</div>

</div>


<!-- ===========================
TERMINAL
=========================== -->



<div class="terminal-line">

● Digital Signature Validated

</div>

<div class="terminal-line success">

✔ Identity Verified Successfully

</div>

</div>

</div>

</div>

</section>
<!-- ============================================================
     PROFESSIONAL SECURITY SECTION WITH INTERACTIVE MODAL
     ============================================================ -->
<!-- ============================================================
     PROFESSIONAL SECURITY SECTION WITH TRIGER BUTTONS
     ============================================================ -->
<section class="security-section">
<div class="container">

    <div class="section-heading" style="text-align: center; margin-bottom: 50px;">
        <span style="color: #38bdf8; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 13px;">OUR SERVICES</span>
        <h2 style="font-size: 36px; font-weight: 800; color: #ffffff; margin-top: 10px;">Enterprise Grade Security Features</h2>
        <p style="color: #94a3b8; font-size: 16px; max-width: 600px; margin: 15px auto 0 auto; line-height: 1.6;">
            AI-powered identity verification platform with military-grade security, fraud detection and real-time analytics.
        </p>
    </div>

    <!-- 4 Column Glowing Grid Layout -->
    <div class="custom-security-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px;">

        <!-- CARD 1 -->
        <div class="security-card" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); padding: 35px 25px; border-radius: 16px; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="security-icon" style="width: 45px; height: 45px; background: rgba(56, 189, 248, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <i class="fa-solid fa-shield-halved" style="font-size: 20px; color: #38bdf8;"></i>
            </div>
            <h3 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 12px;">AI Cryptography</h3>
            <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 25px;">End-to-end military grade encryption protecting enterprise identity.</p>
            
            <button onclick="openModal('ai-crypto')" style="background: transparent; border: none; color: #38bdf8; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 0;">
                Learn More <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
            </button>
        </div>

        <!-- CARD 2 -->
        <div class="security-card" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); padding: 35px 25px; border-radius: 16px; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="security-icon" style="width: 45px; height: 45px; background: rgba(56, 189, 248, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <i class="fa-solid fa-bolt" style="font-size: 20px; color: #38bdf8;"></i>
            </div>
            <h3 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 12px;">Quantum Verification</h3>
            <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 25px;">Ultra-fast AI document verification with real-time validation.</p>
            
            <button onclick="openModal('quantum')" style="background: transparent; border: none; color: #38bdf8; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 0;">
                Learn More <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
            </button>
        </div>

        <!-- CARD 3 -->
        <div class="security-card" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); padding: 35px 25px; border-radius: 16px; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="security-icon" style="width: 45px; height: 45px; background: rgba(56, 189, 248, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <i class="fa-solid fa-chart-line" style="font-size: 20px; color: #38bdf8;"></i>
            </div>
            <h3 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 12px;">Compliance Analytics</h3>
            <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 25px;">Enterprise analytics with fraud monitoring and audit reports.</p>
            
            <button onclick="openModal('compliance')" style="background: transparent; border: none; color: #38bdf8; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 0;">
                Learn More <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
            </button>
        </div>

        <!-- CARD 4 -->
        <div class="security-card" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); padding: 35px 25px; border-radius: 16px; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="security-icon" style="width: 45px; height: 45px; background: rgba(56, 189, 248, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <i class="fa-solid fa-user-shield" style="font-size: 20px; color: #38bdf8;"></i>
            </div>
            <h3 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 12px;">Biometric Security</h3>
            <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 25px;">Multi-layer biometric authentication with secure access control.</p>
            
            <button onclick="openModal('biometric')" style="background: transparent; border: none; color: #38bdf8; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 0;">
                Learn More <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
            </button>
        </div>

    </div>
</div>
</section>

<!-- ============================================================
     JAVASCRIPT POP-UP BOX (MODAL GRAPHICS)
     ============================================================ -->
<div id="securityModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(11, 15, 25, 0.85); backdrop-filter: blur(10px); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #1e293b; border: 1px solid rgba(255,255,255,0.08); max-width: 500px; width: 100%; border-radius: 20px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); text-align: left; position: relative;">
        <span onclick="closeModal()" style="position: absolute; top: 20px; right: 25px; color: #94a3b8; font-size: 24px; cursor: pointer;">&times;</span>
        <h2 id="modalTitle" style="color: #ffffff; font-size: 24px; font-weight: 800; margin-bottom: 15px; font-family: 'Inter', sans-serif;">Service Detail</h2>
        <p id="modalDescription" style="color: #cbd5e1; font-size: 15px; line-height: 1.6; margin-bottom: 30px; font-family: 'Inter', sans-serif;">Information loading...</p>
        <button onclick="closeModal()" style="background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px; width: 100%; box-shadow: 0 4px 15px rgba(37,99,235,0.3);">Close Preview</button>
    </div>
</div>

<script>
const serviceData = {
    'ai-crypto': {
        title: '🛡 Advanced AI Cryptography',
        desc: 'Our system breaks down identities using end-to-end SHA-512 cryptographic keys. Every document data segment uploaded is broken into multi-tenant secure server vaults that can only be unlocked via authorized verification handshakes, providing true government-grade data shielding.'
    },
    'quantum': {
        title: '⚡ Quantum Real-Time Verification',
        desc: 'Utilizing next-generation neural architecture networks, document validation happens instantly. The AI system reads structure matrix data and extracts metadata layers immediately, scanning against structural tampering points within milliseconds for lightning-fast approvals.'
    },
    'compliance': {
        title: '📈 Global Compliance Analytics',
        desc: 'Stay heavily protected under international regulations. This analytical core builds exhaustive fraud score matrix models, instantly warning administrators if documents seem forged or manipulated. Full tamper logs and system node data sheets are archived seamlessly.'
    },
    'biometric': {
        title: '👥 Multi-Layer Biometric Security',
        desc: 'Enforces high-level edge verification protocols. The framework links facial structural landmark analysis with identity file verification scores to prevent deepfakes or stolen physical documents, making sure that the data holder exactly matches official registration database sets.'
    }
};

function openModal(serviceKey) {
    const modal = document.getElementById('securityModal');
    const title = document.getElementById('modalTitle');
    const desc = document.getElementById('modalDescription');
    title.innerText = serviceData[serviceKey].title;
    desc.innerText = serviceData[serviceKey].desc;
    modal.setAttribute('style', 'display: flex !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; background: rgba(11, 15, 25, 0.85) !important; backdrop-filter: blur(10px) !important; z-index: 99999 !important; justify-content: center !important; align-items: center !important; padding: 20px !important;');
}

function closeModal() {
    document.getElementById('securityModal').setAttribute('style', 'display: none !important;');
}
</script>

<style>
.security-card:hover {
    transform: translateY(-5px);

<!-- ============================================================
     JAVASCRIPT POP-UP BOX (MODAL GRAPHICS)
     ============================================================ -->
<div id="securityModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(11, 15, 25, 0.85); backdrop-filter: blur(10px); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #1e293b; border: 1px solid rgba(255,255,255,0.08); max-width: 500px; width: 100%; border-radius: 20px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); text-align: left; position: relative;">
        
        <!-- Close (X) Corner Button -->
        <span onclick="closeModal()" style="position: absolute; top: 20px; right: 25px; color: #94a3b8; font-size: 24px; cursor: pointer; transition: 0.2s;">&times;</span>
        
        <!-- Dinamic Header Pop-Up Header -->
        <h2 id="modalTitle" style="color: #ffffff; font-size: 24px; font-weight: 800; margin-bottom: 15px;">Service Detail</h2>
        
        <!-- Dinamic Pop-Up Content Inside Text -->
        <p id="modalDescription" style="color: #cbd5e1; font-size: 15px; line-height: 1.6; margin-bottom: 30px;">Information loading...</p>
        
        <button onclick="closeModal()" style="background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px; width: 100%; box-shadow: 0 4px 15px rgba(37,99,235,0.3);">
            Close Preview
        </button>
    </div>
</div>

<!-- ============================================================
     MODAL CONTROLLER ENGINE (POP-UP CONTROLLER)
     ============================================================ -->
<script>
// सभी कार्ड्स का विस्तृत डेटा स्टोर करने वाला ऑब्जेक्ट
const serviceData = {
    'ai-crypto': {
        title: '🛡 Advanced AI Cryptography',
        desc: 'Our system breaks down identities using end-to-end SHA-512 cryptographic keys. Every document data segment uploaded is broken into multi-tenant secure server vaults that can only be unlocked via authorized verification handshakes, providing true government-grade data shielding.'
    },
    'quantum': {
        title: '⚡ Quantum Real-Time Verification',
        desc: 'Utilizing next-generation neural architecture networks, document validation happens instantly. The AI system reads structure matrix data and extracts metadata layers immediately, scanning against structural tampering points within milliseconds for lightning-fast approvals.'
    },
    'compliance': {
        title: '📈 Global Compliance Analytics',
        desc: 'Stay heavily protected under international regulations. This analytical core builds exhaustive fraud score matrix models, instantly warning administrators if documents seem forged or manipulated. Full tamper logs and system node data sheets are archived seamlessly.'
    },
    'biometric': {
        title: '👥 Multi-Layer Biometric Security',
        desc: 'Enforces high-level edge verification protocols. The framework links facial structural landmark analysis with identity file verification scores to prevent deepfakes or stolen physical documents, making sure that the data holder exactly matches official registration database sets.'
    }
};

function openModal(serviceKey) {
    const modal = document.getElementById('securityModal');
    const title = document.getElementById('modalTitle');
    const desc = document.getElementById('modalDescription');
    
    // डेटा इंजेक्ट करें
    title.innerText = serviceData[serviceKey].title;
    desc.innerText = serviceData[serviceKey].desc;
    
    // पॉपअप को ग्रिड मोड में डिस्प्ले करें (सेंटर करने के लिए)
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('securityModal').style.display = 'none';
}


<!-- ====================================== -->
<!-- DIGITAL TRUST -->
<!-- ====================================== -->

<section class="trust-section">

<div class="container trust-container">

<div class="trust-left">

<span class="section-tag">
DIGITAL TRUST
</span>

<h2>
Government Grade Digital Identity Platform
</h2>

<p>

Secure identity verification platform designed for Government,
Universities, Enterprises and Financial Organizations.

AI powered authentication, encrypted document verification
and real-time fraud detection.

</p>

<ul class="trust-list">

<li><i class="fa-solid fa-circle-check"></i> AI Identity Verification</li>

<li><i class="fa-solid fa-circle-check"></i> Biometric Authentication</li>

<li><i class="fa-solid fa-circle-check"></i> Fraud Detection Engine</li>

<li><i class="fa-solid fa-circle-check"></i> Encrypted Document Storage</li>

</ul>

</div>

<div class="trust-right">

<div class="trust-card">
<h2>99.9%</h2>
<p>Verification Accuracy</p>
</div>

<div class="trust-card">
<h2>50K+</h2>
<p>Documents Verified</p>
</div>

<div class="trust-card">
<h2>100+</h2>
<p>Enterprise Clients</p>
</div>

<div class="trust-card">
<h2>24×7</h2>
<p>Monitoring</p>
</div>

</div>

</div>

</section>


<!-- ====================================== -->
<!-- PLATFORM STATS -->
<!-- ====================================== -->

<section class="stats-section">

<div class="container">

<div class="section-heading">

<span>PLATFORM STATISTICS</span>

<h2>Trusted Across Enterprises</h2>

<p>

Real-time performance statistics of the DigiVerify Platform.

</p>

</div>

<div class="stats-grid">

<?php foreach($stats_data as $stat){ ?>

<div class="stat-card">

<!-- आइकन दिखाने के लिए यह नया बॉक्स यहाँ जोड़ा गया है -->
<div class="stat-icon">
<i class="fa-solid <?php echo $stat[2]; ?>"></i>
</div>

<h2><?php echo $stat[0]; ?></h2>

<p><?php echo $stat[1]; ?></p>

</div>

<?php } ?>

</div>

</div>

</section>
<!-- ============================================================
     1. COMPLIANCE & SECURITY BANNER (NEW FEATURE)
     ============================================================ -->
<div style="background: rgba(15, 23, 42, 0.5); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 25px 20px; text-align: center; backdrop-filter: blur(10px);">
    <div style="max-width: 1200px; margin: auto; display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; color: #64748b; font-size: 13px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">
        <span><i class="fa-solid fa-certificate" style="color: #38bdf8; margin-right: 6px;"></i> ISO 27001 Certified Node</span>
        <span><i class="fa-solid fa-building-shield" style="color: #10b981; margin-right: 6px;"></i> GDPR Compliant Pipeline</span>
        <span><i class="fa-solid fa-server" style="color: #06b6d4; margin-right: 6px;"></i> SLA 99.99% Node Health</span>
    </div>
</div>

<!-- ============================================================
     2. ENTERPRISE FAQ SECTION (NEW INTERACTIVE FEATURE)
     ============================================================ -->
<section style="padding: 80px 20px; position: relative; z-index: 10;">
    <div style="max-width: 800px; margin: auto;">
        
        <!-- सेक्शन हेडिंग -->
        <div style="text-align: center; margin-bottom: 40px;">
            <span style="color: #06b6d4; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 13px;">FAQ BOARD</span>
            <h2 style="font-size: 32px; font-weight: 800; color: #ffffff; margin-top: 10px;">Frequently Asked Questions</h2>
        </div>

        <!-- FAQ आइटम 1 -->
        <div class="faq-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: 0.3s;">
            <div onclick="toggleFaq(this)" style="padding: 20px; color: #ffffff; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                <span>How long does the AI document verification process take?</span>
                <i class="fa-solid fa-chevron-down" style="font-size: 14px; color: #38bdf8; transition: 0.3s;"></i>
            </div>
            <div class="faq-answer" style="max-height: 0; padding: 0 20px; color: #94a3b8; font-size: 14.5px; line-height: 1.6; transition: all 0.3s ease-out; overflow: hidden;">
                <p style="padding-bottom: 20px;">The AI Core processing module extracts data, validates cryptography, and runs anti-tamper compliance scans in less than 2.5 seconds. For official records matching, results are delivered instantly in real-time.</p>
            </div>
        </div>

        <!-- FAQ आइटम 2 -->
        <div class="faq-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: 0.3s;">
            <div onclick="toggleFaq(this)" style="padding: 20px; color: #ffffff; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                <span>Is my uploaded identity document data safe with DigiVerify?</span>
                <i class="fa-solid fa-chevron-down" style="font-size: 14px; color: #38bdf8; transition: 0.3s;"></i>
            </div>
            <div class="faq-answer" style="max-height: 0; padding: 0 20px; color: #94a3b8; font-size: 14.5px; line-height: 1.6; transition: all 0.3s ease-out; overflow: hidden;">
                <p style="padding-bottom: 20px;">Absolutely. All documents are immediately shredded into isolated byte-hashes using enterprise SHA-512 military encryption vaults. We never save raw copies or trade corporate identity data assets.</p>
            </div>
        </div>

        <!-- FAQ आइटम 3 -->
        <div class="faq-item" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: 0.3s;">
            <div onclick="toggleFaq(this)" style="padding: 20px; color: #ffffff; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                <span>What happens if a document fails verification?</span>
                <i class="fa-solid fa-chevron-down" style="font-size: 14px; color: #38bdf8; transition: 0.3s;"></i>
            </div>
            <div class="faq-answer" style="max-height: 0; padding: 0 20px; color: #94a3b8; font-size: 14.5px; line-height: 1.6; transition: all 0.3s ease-out; overflow: hidden;">
                <p style="padding-bottom: 20px;">If the document fails biometric validation or shows structural edits, the system logs it as "Rejected" and updates the database with a suspicious fraud score. Administrators can review it manually inside the Edge Dashboard.</p>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     3. JAVASCRIPT & HOVER INTERACTION ENGINE
     ============================================================ -->
<script>
function toggleFaq(element) {
    const item = element.parentElement;
    const answer = item.querySelector('.faq-answer');
    const icon = element.querySelector('i');
    
    // सभी अन्य खुले FAQ को बंद करने के लिए
    document.querySelectorAll('.faq-item').forEach(el => {
        if (el !== item) {
            el.querySelector('.faq-answer').style.maxHeight = null;
            el.querySelector('i').style.transform = 'rotate(0deg)';
            el.style.borderColor = 'rgba(255,255,255,0.05)';
        }
    });

    // चालू वाले को टॉगल करें
    if (answer.style.maxHeight) {
        answer.style.maxHeight = null;
        icon.style.transform = 'rotate(0deg)';
        item.style.borderColor = 'rgba(255,255,255,0.05)';
    } else {
        answer.style.maxHeight = answer.scrollHeight + "px";
        icon.style.transform = 'rotate(180deg)';
        item.style.borderColor = 'rgba(56, 189, 248, 0.3)'; // निऑन बॉर्डर इफ़ेक्ट
    }
}
</script>

<style>
/* FAQ बॉक्स होवर स्टाइल */
.faq-item:hover {
    background: rgba(15, 23, 42, 0.8) !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}
</style>


<!-- ====================================== -->
<!-- FOOTER -->
<!-- ====================================== -->

<footer>
   <!-- ============================================================
     ENTERPRISE FOOTER WITH DEVELOPER TAG
     ============================================================ -->
<footer style="margin-top: 60px; background: #070a13; color: white; padding: 40px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); position: relative; z-index: 10;">
    <h2 style="font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">🛡 Enterprise DigiVerify</h2>
    <p style="color: #64748b; font-size: 14px; margin-top: 5px;">AI Based Digital Identity & Document Verification Platform</p>
    
    <!-- आपका डेवलपर टैग (चमकदार स्काई ब्लू निऑन कलर में) -->
    <p style="color: #94a3b8; font-size: 13.5px; margin-top: 15px; font-weight: 500;">
        Developed by <span style="color: #38bdf8; font-weight: 700; text-shadow: 0 0 10px rgba(56, 189, 248, 0.3);">Riddhi Kamble</span>
    </p>
    
    <p style="color: #475569; font-size: 12px; margin-top: 15px;">© <?php echo date("Y");?> All Rights Reserved</p>
</footer>


</footer>
<script>
  function loadContactPage() {
    fetch('contact.php') 
      .then(response => response.text())
      .then(html => {
        document.body.innerHTML = html; // यह बिना फुल स्क्रीन तोड़े पूरा कांटेक्ट पेज लोड कर देगा
      });
  }
</script>





</body>
<!-- ============================================================
     फोर्स जावास्क्रिप्ट इंजन (FORCED MODAL ENGINE)
     ============================================================ -->
<script>
function openModal(serviceKey) {
    // पुराना कोड ढूंढने और उसे चालू करने के लिए
    var modal = document.getElementById('securityModal');
    var title = document.getElementById('modalTitle');
    var desc = document.getElementById('modalDescription');
    
    // सभी चारों कार्ड्स का सटीक डेटा लोड करना
    var forcedData = {
        'ai-crypto': {
            title: '🛡 Advanced AI Cryptography',
            desc: 'Our system breaks down identities using end-to-end SHA-512 cryptographic keys. Every document data segment uploaded is broken into multi-tenant secure server vaults.'
        },
        'quantum': {
            title: '⚡ Quantum Real-Time Verification',
            desc: 'Utilizing next-generation neural architecture networks, document validation happens instantly. The AI system reads structure matrix data immediately.'
        },
        'compliance': {
            title: '📈 Global Compliance Analytics',
            desc: 'Stay heavily protected under international regulations. This analytical core builds exhaustive fraud score matrix models, instantly warning administrators.'
        },
        'biometric': {
            title: '👥 Multi-Layer Biometric Security',
            desc: 'Enforces high-level edge verification protocols. The framework links facial structural landmark analysis with identity file verification scores.'
        }
    };
    
    if(modal && forcedData[serviceKey]) {
        title.innerText = forcedData[serviceKey].title;
        desc.innerText = forcedData[serviceKey].desc;
        
        // इसे स्क्रीन पर जबरन दिखाने के लिए इम्पॉर्टेंट स्टाइल लगाना
        modal.setAttribute('style', 'display: flex !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; background: rgba(11, 15, 25, 0.85) !important; backdrop-filter: blur(10px) !important; z-index: 99999 !important; justify-content: center !important; align-items: center !important; padding: 20px !important;');
    }
}

function closeModal() {
    var modal = document.getElementById('securityModal');
    if(modal) {
        modal.setAttribute('style', 'display: none !important;');
    }
}
</script>


</html>