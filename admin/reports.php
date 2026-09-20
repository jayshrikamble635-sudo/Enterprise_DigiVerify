<?php

session_start();

if(!isset($_SESSION['admin_id'])){

    header("Location: login.php");
    exit();

}

include("../database/config.php");


// Total Documents

$total = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM documents 
WHERE is_deleted=0
")
)['total'];


// Approved

$approved = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM documents 
WHERE status='Approved' 
AND is_deleted=0
")
)['total'];


// Pending

$pending = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM documents 
WHERE status='Pending'
AND is_deleted=0
")
)['total'];


// Rejected

$rejected = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM documents 
WHERE status='Rejected'
AND is_deleted=0
")
)['total'];


// Recent Reports

$result=mysqli_query($conn,"
SELECT *
FROM documents
WHERE is_deleted=0
ORDER BY id DESC
LIMIT 10
");


?>


<!DOCTYPE html>
<html>

<head>

<title>Reports</title>

<link rel="stylesheet" href="css/admin.css">

</head>


<body>


<?php include("includes/sidebar.php"); ?>


<div class="main-content">


<div class="top-header">

<div class="header-left">

<h1>📊 Reports & Analytics</h1>

<p>Document verification reports</p>

</div>


<div class="admin-profile">

<div class="profile-icon">
📊
</div>

<div>

<h4>Administrator</h4>

<span>Enterprise DigiVerify</span>

</div>

</div>


</div>



<div class="stats-grid">


<div class="card">

<span>Total Documents</span>

<h2>
<?php echo $total; ?>
</h2>

</div>


<div class="card">

<span>Approved</span>

<h2>
<?php echo $approved; ?>
</h2>

</div>


<div class="card">

<span>Pending</span>

<h2>
<?php echo $pending; ?>
</h2>

</div>


<div class="card">

<span>Rejected</span>

<h2>
<?php echo $rejected; ?>
</h2>

</div>


</div>




<div class="table-card">


<div class="table-header">

<h3>Recent Document Reports</h3>

</div>


<table>


<thead>

<tr>

<th>User Email</th>

<th>Document</th>

<th>Status</th>

<th>AI Confidence</th>

<th>Fraud Score</th>

<th>Date</th>

</tr>

</thead>



<tbody>


<?php

while($row=mysqli_fetch_assoc($result)){


?>


<tr>


<td>
<?php echo $row['email']; ?>
</td>


<td>
<?php echo $row['document_type']; ?>
</td>


<td>

<span class="status">

<?php echo $row['status']; ?>

</span>

</td>


<td>
<?php echo $row['ai_confidence']; ?>%
</td>


<td>
<?php echo $row['fraud_score']; ?>
</td>


<td>
<?php echo $row['uploaded_at']; ?>
</td>


</tr>


<?php

}

?>


</tbody>


</table>


</div>



</div>


</body>

</html>