<?php

session_start();

include("../database/config.php");


// User ID

$user_id = $_SESSION['user_id'] ?? 1;


// Fetch Documents

$query = "SELECT * FROM documents 
WHERE user_id='$user_id'
ORDER BY uploaded_at DESC";


$result = mysqli_query($conn,$query);


?>


<!DOCTYPE html>
<html>
<head>

<title>Verification Status</title>

<link rel="stylesheet" href="../css/dashboard.css">

<style>

.status-box{

width:700px;
margin:40px auto;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 15px #ccc;

}


.approved{

color:green;
font-weight:bold;

}


.rejected{

color:red;
font-weight:bold;

}


.pending{

color:orange;
font-weight:bold;

}


table{

width:100%;
border-collapse:collapse;

}


th,td{

padding:12px;
border-bottom:1px solid #ddd;

}


</style>

</head>


<body>


<div class="status-box">


<h2>
Document Verification Status
</h2>


<table>


<tr>

<th>Document</th>
<th>Status</th>
<th>Result</th>
<th>Remarks</th>

</tr>



<?php while($row=mysqli_fetch_assoc($result)){ ?>


<tr>


<td>

<?= $row['document_type']; ?>

</td>



<td>


<?php

$status=$row['verification_status'];


if($status=="Approved")
{

echo "<span class='approved'>
Approved
</span>";

}

elseif($status=="Rejected")
{

echo "<span class='rejected'>
Rejected
</span>";

}

else
{

echo "<span class='pending'>
Pending
</span>";

}


?>


</td>


<td>

<?= $row['result']; ?>

</td>


<td>

<?= $row['remarks']; ?>

</td>


</tr>


<?php } ?>


</table>


</div>


</body>

</html>