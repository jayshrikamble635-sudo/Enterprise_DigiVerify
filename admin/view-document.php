<?php
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include("../database/config.php");
include("includes/header.php");
?>
<?php
session_start();
include("../database/config.php");

if(!isset($_GET['id'])){
    die("Invalid Request");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM documents WHERE id='$id'");

if(mysqli_num_rows($result)==0){
    die("Document Not Found");
}

$row = mysqli_fetch_assoc($result);

$file = "../uploads/".$row['document_type']."/".$row['file_name'];
$ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));
?>

<!DOCTYPE html>
<html>
<head>

<title>DigiVerify | View Document</title>

<style>

body{
margin:0;
padding:30px;
font-family:Arial;
background:#0f172a;
color:white;
}

.container{
width:1100px;
margin:auto;
}

.card{
background:#1e293b;
padding:25px;
border-radius:15px;
margin-bottom:25px;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

table{
width:100%;
border-collapse:collapse;
}

td{
padding:12px;
border-bottom:1px solid #334155;
}

td:first-child{
width:220px;
font-weight:bold;
color:#00d4ff;
}

img{
width:100%;
max-height:600px;
object-fit:contain;
border-radius:10px;
background:white;
}

.btn{
padding:12px 22px;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:bold;
color:white;
text-decoration:none;
}

.approve{
background:#16a34a;
}

.reject{
background:#dc2626;
}

textarea{
width:100%;
height:120px;
padding:15px;
border-radius:10px;
border:none;
margin-top:10px;
margin-bottom:20px;
}

</style>

</head>

<body>
    <div class="wrapper">

<?php include("includes/sidebar.php"); ?>

<div class="main-content">

<div class="container">

<h2 style="text-align:center;color:#00d4ff;">
📄 DigiVerify AI Document Verification
</h2>

<div class="card">

<table>

<tr>
<td>Reference ID</td>
<td>DV<?php echo str_pad($row['id'],6,"0",STR_PAD_LEFT); ?></td>
</tr>

<tr>
<td>Email</td>
<td><?php echo htmlspecialchars($row['email']); ?></td>
</tr>

<tr>
<td>Document Type</td>
<td><?php echo strtoupper($row['document_type']); ?></td>
</tr>

<tr>
<td>Status</td>
<td><b><?php echo $row['status']; ?></b></td>
</tr>

<tr>
<td>AI Confidence</td>
<td><?php echo $row['ai_confidence']; ?>%</td>
</tr>

<tr>
<td>Fraud Score</td>
<td><?php echo $row['fraud_score']; ?>%</td>
</tr>

<tr>
<td>Recommendation</td>
<td><?php echo $row['recommendation']; ?></td>
</tr>

<tr>
<td>AI Result</td>
<td><?php echo $row['result']; ?></td>
</tr>

<tr>
<td>Image Quality</td>
<td><?php echo $row['quality']; ?></td>
</tr>

<tr>
<td>QR Status</td>
<td><?php echo $row['qr_status']; ?></td>
</tr>

<tr>
<td>Uploaded Date</td>
<td><?php echo $row['uploaded_at']; ?></td>
</tr>

</table>

</div>

<div class="card">

<h3>Uploaded Document</h3>

<?php

if($ext=="pdf"){

echo "<a href='$file' target='_blank' class='btn approve'>Open PDF</a>";

}else{

echo "<img src='$file'>";

}

?>

</div>

<div class="card">

<h3>OCR Extracted Text</h3>

<div style="background:#111827;padding:20px;border-radius:10px;max-height:250px;overflow:auto;color:#00ff99;line-height:24px;">

<?php echo nl2br(htmlspecialchars($row['ocr_text'])); ?>

</div>

</div>

<div class="card">

<h3>Verification Action</h3>

<form action="update-status.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<label>Remarks</label>

<textarea name="remarks"><?php echo htmlspecialchars($row['remarks']); ?></textarea>

<button class="btn approve" name="status" value="Approved">

✅ Approve

</button>

<button class="btn reject" name="status" value="Rejected">

❌ Reject

</button>

</form>

</div>

</div>
<?php include("includes/footer.php"); ?>

</div>

</div>

</body>
</html>