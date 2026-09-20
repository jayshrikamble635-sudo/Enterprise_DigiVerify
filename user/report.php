<?php

include("../database/config.php");

$id=$_GET['id'];

$result=mysqli_query($conn,"SELECT * FROM documents WHERE id='$id'");

$row=mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>

<html>

<head>

<title>Verification Report</title>

<style>

body{
font-family:Arial;
background:#eef2f7;
}

.container{

width:900px;
margin:40px auto;
background:white;
padding:30px;
border-radius:10px;

}

table{

width:100%;
border-collapse:collapse;

}

td{

padding:15px;
border:1px solid #ddd;

}

.status{

font-size:22px;
font-weight:bold;

}

.approved{

color:green;

}

.rejected{

color:red;

}

.pending{

color:orange;

}

</style>

</head>

<body>

<div class="container">

<h2>Document Verification Report</h2>

<table>

<tr>

<td>Document Type</td>

<td><?php echo strtoupper($row['document_type']); ?></td>

</tr>

<tr>

<td>Quality</td>

<td><?php echo $row['quality']; ?></td>

</tr>

<tr>

<td>QR Status</td>

<td><?php echo $row['qr_status']; ?></td>

</tr>

<tr>

<td>Verification Result</td>

<td><?php echo $row['result']; ?></td>

</tr>

<tr>

<td>Remarks</td>

<td><?php echo nl2br(htmlspecialchars($row['remarks'])); ?></td>

</tr>

<tr>

<td>Status</td>

<td class="status
<?php

if($row['status']=="Approved")
echo " approved";

elseif($row['status']=="Rejected")
echo " rejected";

else
echo " pending";

?>

">

<?php echo $row['status']; ?>

</td>

</tr>

</table>

</div>

</body>

</html>