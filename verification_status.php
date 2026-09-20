<?php
session_start();
include("database/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,"
SELECT *
FROM documents
WHERE user_id='$user_id'
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Verification Status</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="section-card">

<h2>✅ Verification Status</h2>

<table border="1" width="100%">
<tr>
<th>Document</th>
<th>Type</th>
<th>Status</th>
<th>Date</th>
<th>Remark</th>
</tr>

<?php

if(mysqli_num_rows($query)>0)
{

while($row=mysqli_fetch_assoc($query))
{

?>

<tr>

<td>
<?php echo $row['file_name']; ?>
</td>

<td>
<?php echo $row['document_type']; ?>
</td>

<td>
<?php echo $row['status']; ?>
</td>

<td>
<?php echo $row['uploaded_at']; ?>
</td>

<td>
<?php echo $row['remark'] ?? "No Remark"; ?>
</td>

</tr>

<?php
}

}
else
{

echo "
<tr>
<td colspan='5'>
No Documents Uploaded
</td>
</tr>";

}

?>

</table>

</div>

</body>
</html>