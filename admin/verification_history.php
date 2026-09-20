<?php
session_start();
include("../database/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check Admin Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch Verification History
$sql = "
SELECT d.*, u.fullname
FROM documents d
LEFT JOIN users u
ON d.user_id = u.id
ORDER BY d.uploaded_at DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Error : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verification History</title>

<link rel="stylesheet" href="css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">

<div class="top-header">

<div>

<h1><i class="fa-solid fa-file-shield"></i> Verification History</h1>

<p>All verified document records</p>

</div>

</div>

<div class="table-card">

<table>

<thead>

<tr>

<th>Name</th>
<th>Email</th>
<th>Document</th>
<th>Status</th>
<th>Confidence</th>
<th>Recommendation</th>
<th>Uploaded</th>

</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($result)>0){ ?>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td>

<?php
echo !empty($row['fullname']) ? $row['fullname'] : "N/A";
?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['document_type']); ?>

</td>

<td>

<?php

if($row['status']=="Approved"){

echo "<span class='status approved'>Approved</span>";

}
elseif($row['status']=="Pending"){

echo "<span class='status pending'>Pending</span>";

}
else{

echo "<span class='status rejected'>Rejected</span>";

}

?>

</td>

<td>

<?php echo $row['confidence']; ?>%

</td>

<td>

<?php echo htmlspecialchars($row['recommendation']); ?>

</td>

<td>

<?php echo $row['uploaded_at']; ?>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="7" style="text-align:center;padding:20px;">

No verification records found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>

</html>