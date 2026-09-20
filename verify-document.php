<?php
session_start();

include("database/config.php");

// एरर चेक करने के लिए


// ===============================
// CHECK DOCUMENT ID OR EMAIL
// ===============================

$id_exists = isset($_GET['id']) && !empty($_GET['id']);
$email_exists = isset($_GET['email']) && !empty($_GET['email']);

// अगर ID और Email दोनों ही गायब हैं तो एरर दें
if(!$id_exists && !$email_exists)
{
    die("Invalid Verification Request");
}

// ===============================
// FETCH DOCUMENT DATA
// ===============================

// अगर URL में ID है तो ID से खोजें, नहीं तो सिर्फ Email से खोजें
if($id_exists) {
    $doc_id = (int)$_GET['id'];
    $where_clause = "d.id='$doc_id'";
} else {
    $user_email = mysqli_real_escape_string($conn, $_GET['email']);
    $where_clause = "u.email='$user_email'";
}

$sql = "
SELECT 
d.*,
u.fullname,
u.email AS user_email
FROM documents d
LEFT JOIN users u
ON d.user_id = u.id
WHERE $where_clause
LIMIT 1
";

$result = mysqli_query($conn, $sql);



if(!$result)
{
    die(mysqli_error($conn));
}


if(mysqli_num_rows($result)==0)
{
    die("Document Not Found");
}


$row = mysqli_fetch_assoc($result);



// ===============================
// DATA
// ===============================


$reference = "DV".str_pad($row['id'],6,"0",STR_PAD_LEFT);


$name = !empty($row['fullname'])
?
$row['fullname']
:
"Unknown User";


$email = !empty($row['email'])
?
$row['email']
:
$row['user_email'];



// ============================================================
// रिजेक्शन डेटा ऑटोमैटिक सेव और करेक्ट करने का कोड
// ============================================================
if($row['document_type'] == 'UNKNOWN DOCUMENT' || ($row['verification_status'] == 'Pending' && ($row['fraud_score'] ?? 0) >= 20)) {
    $current_id = $row['id'];
    // डेटाबेस में Rejected स्टेटस और रिमार्क सेव करने की क्वेरी
   $update_sql = "UPDATE documents SET 
                verification_status = 'Rejected', 
                status = 'Rejected',
                remarks = 'REJECTED: Critical Fail! Document format is invalid or unrecognizable.'
               WHERE id = '$current_id'";

    mysqli_query($conn, $update_sql);
    
    // लोकल वेरिएबल्स को तुरंत अपडेट करें ताकि इसी पेज पर तुरंत रिजेक्ट दिखे
    $row['verification_status'] = 'Rejected';
    $row['remarks'] = 'REJECTED: Critical Fail! Document format is invalid or unrecognizable.';
}

$status = $row['verification_status'];



$statusColor="#f59e0b";
$statusIcon="⏳";


if($status=="Approved")
{
    $statusColor="#16a34a";
    $statusIcon="✔";
}


if($status=="Rejected")
{
    $statusColor="#dc2626";
    $statusIcon="✖";
}



$confidence = $row['ai_confidence'] ?? 0;

$fraud = $row['fraud_score'] ?? 0;

$remarks = $row['remarks'] ?? "No Remarks";

$recommendation = $row['recommendation'] ?? "Pending";

$document_number = $row['extracted_document_number'] ?? "Not Extracted";


?>


<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Enterprise DigiVerify Verification
</title>


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">


<style>
/* ============================================================
   DIGIVERIFY HOMEPAGE GLITTERY THEME FOR VERIFICATION RESULT
   ============================================================ */
@import url('https://googleapis.com');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Inter', sans-serif;
}

body {
    background-color: #0b0f19; /* होमपेज डार्क नाइट कलर */
    /* ग्लिटरी ग्रिड लाइन इफ़ेक्ट */
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 30px 30px;
    color: #f8fafc;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
}

/* बैकग्राउंड निऑन ग्लो लाइट्स */
body::before, body::after {
    content: "";
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    filter: blur(140px);
    z-index: -1;
    opacity: 0.25;
}
body::before {
    top: 10%;
    left: 5%;
    background: #2563eb; /* रॉयल ब्लू ग्लो */
}
body::after {
    bottom: 20%;
    right: 5%;
    background: #06b6d4; /* कयान निऑन ग्लो */
}

/* हेडर को होमपेज नेवबार जैसा पारदर्शी और डार्क लुक */
header {
    background: rgba(15, 23, 42, 0.6);
    padding: 20px 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
}

.logo {
    font-size: 28px;
    font-weight: 800;
}

.logo span {
    color: #38bdf8;
}

nav a {
    color: #94a3b8;
    text-decoration: none;
    margin-left: 25px;
    font-weight: 500;
    transition: 0.3s;
}

nav a:hover {
    color: #ffffff;
}

/* हीरो सेक्शन को मॉडर्न बनाना */
.hero {
    padding: 60px 20px;
    text-align: center;
    color: white;
}

.hero h1 {
    font-size: 42px;
    font-weight: 800;
    letter-spacing: -1px;
}

.hero p {
    color: #94a3b8;
    margin-top: 10px;
    font-size: 16px;
}

.container {
    padding: 20px 20px 60px 20px;
}

/* मुख्य वाइट कार्ड को डार्क ग्लास-मोर्टफिज़्म लुक देना */
.verify-card {
    max-width: 850px;
    background: rgba(15, 23, 42, 0.75) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 20px !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(37, 99, 235, 0.05) !important;
    margin: auto;
    padding: 40px;
    backdrop-filter: blur(12px);
}

.verify-header h2 {
    font-size: 30px;
    color: #ffffff;
    font-weight: 700;
}

.verify-header p {
    color: #94a3b8;
    margin-top: 5px;
}

.icon {
    font-size: 65px;
    margin-bottom: 10px;
}

/* स्टेटस बैज (Approved / Rejected) निऑन स्टाइल */
.status-badge {
    width: max-content;
    margin: 25px auto;
    padding: 10px 35px;
    border-radius: 30px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* डेटा टेबल का नया लुक */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

td {
    padding: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    color: #e2e8f0;
    font-size: 14px;
    text-align: left;
}

/* लेफ्ट साइड के लेबल को स्काई ब्लू निऑन कलर देना */
td:first-child {
    font-weight: 600;
    color: #38bdf8;
    width: 240px;
}

/* राइट साइड का डेटा */
td:last-child {
    color: #ffffff;
}

/* प्रोग्रेस बार */
.progress {
    height: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #2563eb, #06b6d4);
    color: white;
    text-align: center;
    font-weight: 600;
    font-size: 12px;
    line-height: 18px;
}

/* सिक्योर बॉक्स */
.secure-box {
    margin-top: 30px;
    padding: 20px;
    background: rgba(37, 99, 235, 0.05);
    border-left: 4px solid #2563eb;
    border-radius: 10px;
    text-align: left;
}

.secure-box h3 {
    color: #ffffff;
    font-size: 16px;
}

.secure-box p {
    color: #94a3b8;
    font-size: 14px;
    margin-top: 5px;
}

/* बटन्स */
.buttons {
    text-align: center;
    margin-top: 35px;
}

.btn {
    display: inline-block;
    padding: 12px 30px;
    border-radius: 10px;
    color: white;
    text-decoration: none;
    margin: 10px;
    font-weight: 600;
    font-size: 14px;
    transition: 0.3s ease;
}

.home {
    background: linear-gradient(135deg, #2563eb, #06b6d4);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
}

.home:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(6, 182, 212, 0.5);
}

.login {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.login:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* फुटर */
footer {
    margin-top: 60px;
    background: #070a13;
    color: white;
    padding: 40px;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

footer h2 {
    font-size: 22px;
    font-weight: 700;
}

footer p {
    color: #64748b;
    font-size: 14px;
    margin-top: 5px;
}
</style>


</head>



<body>


<header>


<div class="logo">

🛡 Enterprise <span>DigiVerify</span>

</div>


<nav>

<a href="index.php">
Home
</a>

<a href="about.php">
About
</a>

<a href="services.php">
Services
</a>


</nav>


</header>




<section class="hero">


<h1>
Digital Identity Verification
</h1>


<p>
AI Powered Document Verification Platform
</p>


</section>





<div class="container">


<div class="verify-card">



<div class="verify-header">


<div class="icon">

<?php echo $statusIcon; ?>

</div>


<h2>
Document Verification Result
</h2>


<p>
Enterprise DigiVerify AI Report
</p>


</div>




<div class="status-badge"
style="background:<?php echo $statusColor;?>">


<?php echo $status; ?>


</div>





<table>


<tr>
<td>Reference ID</td>
<td><?php echo $reference;?></td>
</tr>


<tr>
<td>Full Name</td>
<td><?php echo htmlspecialchars($name);?></td>
</tr>


<tr>
<td>Email</td>
<td><?php echo htmlspecialchars($email);?></td>
</tr>



<tr>
<td>Document Type</td>
<td><?php echo $row['document_type'];?></td>
</tr>



<tr>
<td>Document Number</td>
<td><?php echo $document_number;?></td>
</tr>




<tr>

<td>
AI Confidence
</td>


<td>

<div class="progress">

<div class="progress-bar"
style="width:<?php echo $confidence;?>%">

<?php echo $confidence;?>%

</div>

</div>

</td>

</tr>




<tr>

<td>
Fraud Score
</td>


<td>
<?php echo $fraud;?>%
</td>

</tr>




<tr>

<td>
Recommendation
</td>


<td>
<?php echo $recommendation;?>
</td>


</tr>




<tr>

<td>
Remarks
</td>


<td>
<?php echo $remarks;?>
</td>


</tr>



<tr>

<td>
Uploaded Date
</td>


<td>
<?php echo $row['uploaded_at'];?>
</td>

</tr>



</table>




<div class="secure-box">

<h3>
🔒 Secure Verification
</h3>


<p>

This record is generated by Enterprise DigiVerify AI verification system.

</p>


</div>




<div class="buttons">


<a class="btn home" href="index.php">

🏠 Home

</a>



<a class="btn login" href="user/login.php">

👤 Login

</a>


</div>



</div>


</div>




<footer>

<h2>
🛡 Enterprise DigiVerify
</h2>

<p>
AI Based Digital Identity & Document Verification Platform
</p>

<p>
© <?php echo date("Y");?> All Rights Reserved
</p>


</footer>



</body>

</html>