<?php
include("../database/config.php");

$resultData = null;

if (isset($_GET['email']) && $_GET['email'] != "") {

    $email = mysqli_real_escape_string($conn, $_GET['email']);

    $query = "SELECT * FROM documents WHERE email='$email' ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $resultData = $result;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Check Verification Status</title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
    padding:30px;
}

.container{
    width:900px;
    margin:auto;
    background:#1e293b;
    padding:30px;
    border-radius:12px;
}

input{
    width:60%;
    padding:12px;
    border-radius:8px;
    border:none;
}

button{
    padding:12px 20px;
    background:#00d4ff;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

table{
    width:100%;
    margin-top:25px;
    border-collapse:collapse;
}

td,th{
    border:1px solid #444;
    padding:10px;
    text-align:center;
}

.approved{color:lime;font-weight:bold;}
.rejected{color:red;font-weight:bold;}
.pending{color:orange;font-weight:bold;}

</style>
</head>

<body>

<div class="container">

<h2>🔍 Check Document Verification Status</h2>

<form method="GET">
<input type="email" name="email" placeholder="Enter your email..." required>
<button type="submit">Search</button>
</form>

<?php if ($resultData) { ?>

<table>
    <th>Reference ID</th>
    <td>
DV<?php echo str_pad($row['id'],6,"0",STR_PAD_LEFT); ?>
</td>
<th>QR Code</th>
<td>

<?php
if($row['status']=="Approved"){
?>

<img
src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=DV<?php echo $row['id']; ?>"
width="80">

<?php
}else{

echo "--";

}
?>

</td>
<th>Certificate</th>
<td>

<?php

if($row['status']=="Approved"){

?>

<a
href="certificate.php?id=<?php echo $row['id'];?>"
style="
background:#16a34a;
padding:8px 15px;
color:white;
border-radius:8px;
text-decoration:none;">

Download

</a>

<?php

}else{

echo "--";

}

?>

</td>

<tr>
<th>ID</th>
<th>Document</th>
<th>AI Confidence</th>
<th>Fraud Score</th>
<th>Result</th>
<th>Status</th>
<th>Verified By</th>
<th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($resultData)) { ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo strtoupper($row['document_type']); ?></td>

<td><?php echo $row['ai_confidence']; ?>%</td>

<td><?php echo $row['fraud_score']; ?>%</td>

<td><?php

if($row['result']=="Likely Genuine"){

echo "<span style='color:#00ff99;font-weight:bold;'>✔ Verified</span>";

}
elseif($row['result']=="Manual Review"){

echo "<span style='color:orange;font-weight:bold;'>⏳ Review</span>";

}
else{

echo "<span style='color:red;font-weight:bold;'>✖ Fake</span>";

}

?></td>

<td class="<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>

<td><?php echo $row['verified_by']; ?></td>
<td><?php

echo $row['verified_at'] ? $row['verified_at'] : "Pending";

?></td>
</tr>

<?php } ?>

</table>

<?php } elseif(isset($_GET['email'])) { ?>

<p style="color:red;margin-top:20px;">No records found for this email.</p>

<?php } ?>

</div>

</body>
</html>