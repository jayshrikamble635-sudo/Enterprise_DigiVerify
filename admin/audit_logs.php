<?php

session_start();

include("../database/config.php");


/* ADMIN LOGIN CHECK */

if(!isset($_SESSION['admin_id'])){

    header("Location: login.php");
    exit();

}


/* FETCH AUDIT LOGS */

$query = "SELECT * FROM audit_logs ORDER BY id DESC";

$result = mysqli_query($conn,$query);


?>


<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Audit Logs | DigiVerify Admin</title>


<link rel="stylesheet" href="../css/admin.css">


<style>

body{

    background:#08111f;
    color:white;
    font-family:Arial,sans-serif;

}


.container{

    padding:30px;

}


.section-card{

    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(15px);
    border-radius:15px;
    padding:25px;
    border:1px solid rgba(255,255,255,0.2);

}


h2{

    margin-bottom:20px;

}


.table-container{

    overflow-x:auto;

}


table{

    width:100%;
    border-collapse:collapse;

}


th{

    background:#2563eb;
    padding:14px;
    text-align:left;

}


td{

    padding:12px;
    border-bottom:1px solid rgba(255,255,255,0.15);

}


tr:hover{

    background:rgba(255,255,255,0.08);

}


.badge{

    padding:6px 12px;
    border-radius:20px;
    background:#22c55e;
    color:white;
    font-size:13px;

}


</style>


</head>


<body>


<div class="container">


<div class="section-card">


<h2>📜 Audit Logs</h2>


<div class="table-container">


<table>


<tr>

<th>ID</th>

<th>Document ID</th>

<th>Action</th>

<th>Admin Name</th>

<th>Remarks</th>

<th>Date & Time</th>

</tr>



<?php


if(mysqli_num_rows($result)>0){


while($row=mysqli_fetch_assoc($result)){


?>


<tr>


<td>
<?php echo $row['id']; ?>
</td>


<td>
<?php echo $row['document_id']; ?>
</td>


<td>

<span class="badge">

<?php echo $row['action']; ?>

</span>

</td>


<td>
<?php echo $row['admin_name']; ?>
</td>


<td>
<?php echo $row['remarks']; ?>
</td>


<td>
<?php echo $row['action_time']; ?>
</td>


</tr>


<?php

}

}

else{

?>

<tr>

<td colspan="6">

No Audit Logs Found

</td>

</tr>


<?php

}


?>


</table>


</div>


</div>


</div>


</body>

</html>