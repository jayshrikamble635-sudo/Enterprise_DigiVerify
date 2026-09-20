<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn,"
SELECT documents.*, users.email
FROM documents
LEFT JOIN users
ON documents.user_id = users.id
WHERE documents.is_deleted=1
ORDER BY documents.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Deleted Documents</title>

<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="section-card">

<div class="section-header">

<h2>🗑 Deleted Documents</h2>

<p>Restore or permanently remove deleted documents.</p>

</div>

<div class="table-container">

<table>

<tr>

<th>ID</th>

<th>User</th>

<th>Document</th>

<th>Status</th>

<th>Deleted</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['email']); ?></td>

<td><?php echo strtoupper($row['document_type']); ?></td>

<td><?php echo $row['status']; ?></td>

<td>✅ Deleted</td>

<td>

<a
class="btn1"
href="restore-document.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Restore this document?');">

♻ Restore

</a>

<a
class="btn2"
href="permanent-delete.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Permanently delete this document?');">

❌ Delete Forever

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<?php include("includes/footer.php"); ?>

</div>

</body>

</html>