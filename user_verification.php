<?php
session_start();

include("database/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$result = mysqli_query($conn,"
SELECT *
FROM documents
WHERE user_id='$user_id'
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>User Verification Status</title>

<link rel="stylesheet" href="css/user.css">

</head>


<body>


<div class="main">


<div class="topbar">

<h2>✅ User Verification Status</h2>

<p>Track your document verification process.</p>

</div>


<table>


<tr>

<th>Document Type</th>

<th>Status</th>

<th>AI Confidence</th>

<th>Result</th>

<th>Recommendation</th>

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
<?php echo $row['document_type']; ?>
</td>


<td>
<?php echo $row['status']; ?>
</td>


<td>
<?php echo $row['ai_confidence']; ?>%
</td>


<td>
<?php echo $row['result']; ?>
</td>


<td>
<?php echo $row['recommendation']; ?>
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

echo "
<tr>
<td colspan='6'>
No Documents Uploaded
</td>
</tr>
";

}

?>


</table>


</div>


</body>

</html>