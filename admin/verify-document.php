<?php

include("../database/config.php");

$id=$_GET['id'];

$result=mysqli_query($conn,"SELECT * FROM documents WHERE id='$id'");

$row=mysqli_fetch_assoc($result);

$image="../uploads/".$row['document_type']."/".$row['file_name'];

?>

<!DOCTYPE html>

<html>

<head>

<title>Verify Document</title>

<style>

body{

background:#f3f6fb;

font-family:Arial;

}

.container{

width:1100px;

margin:30px auto;

background:white;

padding:30px;

border-radius:10px;

}

.left{

width:45%;

float:left;

}

.right{

width:50%;

float:right;

}

img{

width:100%;

border-radius:10px;

}

table{

width:100%;

border-collapse:collapse;

margin-top:20px;

}

td{

padding:12px;

border:1px solid #ddd;

}

button{

padding:12px 30px;

margin-top:20px;

margin-right:10px;

cursor:pointer;

}

</style>

</head>

<body>

<div class="container">

<div class="left">

<img src="<?php echo $image; ?>">

</div>
<hr>

<h2>Verification Result</h2>

<form action="save-verification.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<label>Document Quality</label><br>

<select name="quality" required>
    <option value="">Select</option>
    <option>Good</option>
    <option>Blur</option>
    <option>Damaged</option>
</select>

<br><br>

<label>QR Code</label><br>

<select name="qr_status" required>
    <option value="">Select</option>
    <option>Valid</option>
    <option>Invalid</option>
    <option>Not Found</option>
</select>

<br><br>

<label>Document Result</label><br>

<select name="result" required>
    <option value="">Select</option>
    <option>Original</option>
    <option>Fake</option>
    <option>Need Manual Review</option>
</select>

<br><br>

<label>Admin Remarks</label><br>

<textarea name="remarks" rows="5" cols="60"></textarea>

<br><br>

<button type="submit">Save Verification</button>

</form>

<div class="right">

<h2>Verification Panel</h2>

<table>

<tr>

<td>Document Type</td>

<td><?php echo strtoupper($row['document_type']); ?></td>

</tr>

<tr>

<td>Status</td>

<td><?php echo $row['status']; ?></td>

</tr>

<tr>

<td>OCR Result</td>

<td>Pending</td>

</tr>

<tr>

<td>QR Verification</td>

<td>Pending</td>

</tr>

<tr>

<td>AI Detection</td>

<td>Pending</td>

</tr>

<tr>

<td>Fraud Score</td>

<td>0%</td>

</tr>

</table>

<br>

<a href="approve.php?id=<?php echo $row['id']; ?>">

<button style="background:green;color:white;">

Approve

</button>

</a>

<a href="reject.php?id=<?php echo $row['id']; ?>">

<button style="background:red;color:white;">

Reject

</button>

</a>

</div>

<div style="clear:both;"></div>

</div>

</body>

</html>