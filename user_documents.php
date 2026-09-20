<?php
session_start();

include("database/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$result=mysqli_query($conn,"
SELECT *
FROM documents
WHERE user_id='$user_id'
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>User Documents</title>

<link rel="stylesheet" href="css/user.css">

</head>

<body>


<div class="main">


<h2>📄 My Documents</h2>


<table>

<tr>

<th>ID</th>
<th>Document Type</th>
<th>File</th>
<th>Status</th>
<th>Date</th>

</tr>


<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>


<td>
<?php echo $row['document_type']; ?>
</td>


<td>

<a href="uploads/<?php echo $row['file_name']; ?>" target="_blank">
👁 View
</a>

</td>


<td>
<?php echo $row['status']; ?>
</td>


<td>
<?php echo $row['uploaded_at']; ?>
</td>


</tr>


<?php

}

}
else
{

?>

<tr>
<td colspan="5">
No Documents Uploaded
</td>
</tr>

<?php

}

?>

</table>


</div>


</body>

</html>