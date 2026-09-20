<?php
include("database/config.php");

if(!isset($_GET['id'])){
    die("Invalid Verification");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM documents WHERE id='$id'");

if(mysqli_num_rows($result)==0){
    die("Record Not Found");
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<title>DigiVerify Verification</title>

<style>

body{
font-family:Arial;
background:#0f172a;
color:white;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.card{

width:600px;
background:#1e293b;
padding:35px;
border-radius:15px;
text-align:center;

}

table{

width:100%;
margin-top:20px;
border-collapse:collapse;

}

td{

padding:12px;
border-bottom:1px solid #334155;

}

</style>

</head>

<body>

<div class="card">

<h1>✅ DigiVerify</h1>

<h2>Digital Verification Result</h2>

<table>

<tr>

<td>Reference ID</td>

<td>

DV<?php echo str_pad($row['id'],6,"0",STR_PAD_LEFT); ?>

</td>

</tr>

<tr>

<td>Email</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

</tr>

<tr>

<td>Document</td>

<td>

<?php echo strtoupper($row['document_type']); ?>

</td>

</tr>

<tr>

<td>Status</td>

<td>

<?php echo $row['status']; ?>

</td>

</tr>

<tr>

<td>AI Confidence</td>

<td>

<?php echo $row['ai_confidence']; ?>%

</td>

</tr>

<tr>

<td>Verified By</td>

<td>

<?php echo $row['verified_by']; ?>

</td>

</tr>

</table>

</div>

</body>

</html>