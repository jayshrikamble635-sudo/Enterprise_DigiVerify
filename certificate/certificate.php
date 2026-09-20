<?php
session_start();
include("../database/config.php");

if(!isset($_GET['id'])){
    die("Invalid Request");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"
SELECT * FROM documents
WHERE id='$id'
");

if(mysqli_num_rows($result)==0){
    die("Certificate Not Found");
}

$row = mysqli_fetch_assoc($result);

if($row['status']!="Approved"){
    die("Certificate Available Only For Approved Documents");
}

$reference = "DV".str_pad($row['id'],6,"0",STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html>

<head>

<title>DigiVerify Certificate</title>

<style>

body{
margin:0;
padding:40px;
font-family:Arial;
background:#e5e7eb;
}

.certificate{

width:900px;
margin:auto;

background:white;

padding:50px;

border:12px solid #2563eb;

border-radius:20px;

box-shadow:0 0 30px rgba(0,0,0,.3);

}

h1{

text-align:center;

color:#1e3a8a;

}

h2{

text-align:center;

color:#16a34a;

}

table{

width:100%;

margin-top:30px;

border-collapse:collapse;

}

td{

padding:15px;

border:1px solid #ddd;

}

.btn{

display:inline-block;

margin-top:30px;

padding:14px 25px;

background:#2563eb;

color:white;

text-decoration:none;

border-radius:8px;

font-weight:bold;

}

</style>

</head>

<body>

<div class="certificate">

<h1>DigiVerify</h1>

<h2>Digital Verification Certificate</h2>

<table>

<tr>

<td width="250"><b>Reference ID</b></td>

<td><?php echo $reference; ?></td>

</tr>

<tr>

<td><b>Email</b></td>

<td><?php echo $row['email']; ?></td>

</tr>

<tr>

<td><b>Document Type</b></td>

<td><?php echo strtoupper($row['document_type']); ?></td>

</tr>

<tr>

<td><b>AI Confidence</b></td>

<td><?php echo $row['ai_confidence']; ?>%</td>

</tr>

<tr>

<td><b>Recommendation</b></td>

<td><?php echo $row['recommendation']; ?></td>

</tr>

<tr>

<td><b>Verified By</b></td>

<td><?php echo $row['verified_by']; ?></td>

</tr>

<tr>

<td><b>Verification Date</b></td>

<td><?php echo $row['verified_at']; ?></td>

</tr>


<tr>

<td><b>Fraud Score</b></td>

<td>

<?php echo $row['fraud_score']; ?>%

</td>

</tr>
<tr>

<td><b>Image Quality</b></td>

<td>

<?php echo $row['quality']; ?>

</td>

</tr>
<td><b>Status</b></td>

<td style="color:green;font-weight:bold;font-size:20px;">

✅ VERIFIED

</td>

</tr>

</table>

<br><br>

<h3 align="center">

QR Verification

</h3>

<div align="center">

<?php

$qrFile = "../assets/qr/".$reference.".png";

if(file_exists($qrFile)){
?>

<img
src="<?php echo $qrFile; ?>"
width="180">

<?php
}
?>
</div>

<br>

<button onclick="window.print()" class="btn">

🖨 Print / Save PDF

</button>

</div>

</div>

</body>

</html>