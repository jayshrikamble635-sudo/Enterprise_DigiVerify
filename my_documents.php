<?php

session_start();

include("database/config.php");


if(!isset($_SESSION['user_id'])){
    header("Location: user/login.php");

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

<title>My Documents</title>
<link rel="stylesheet" href="css/style.css">

<style>

body{
    background:#050b1f;
    color:white;
    font-family:Arial;
}

.container{
    width:90%;
    margin:40px auto;
}

.box{

    background:#111a35;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 20px #00d4ff;

}

h1{
    text-align:center;
    color:#00d4ff;
}


table{

    width:100%;
    border-collapse:collapse;

}


th{

    background:#00d4ff;
    color:black;
    padding:12px;

}


td{

    padding:12px;
    text-align:center;
    border-bottom:1px solid #444;

}


a{

    color:#00d4ff;
    text-decoration:none;

}


.status{

    padding:6px 15px;
    border-radius:20px;

}


.pending{

    background:orange;
    color:black;

}


.approved{

    background:green;

}


.rejected{

    background:red;

}


</style>

</head>


<body>


<div class="container">

<div class="box">


<h1>📄 My Documents</h1>


<table>


<tr>

<th>ID</th>
<th>Document Type</th>
<th>File</th>
<th>Status</th>
<th>QR Code</th>
<th>Date</th>

</tr>



<?php


if(mysqli_num_rows($query)>0){


while($row=mysqli_fetch_assoc($query)){


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

View File

</a>

</td>


<td>


<?php

$status=$row['status'];


if($status=="Approved"){

echo "<span class='status approved'>Approved</span>";

}

elseif($status=="Rejected"){

echo "<span class='status rejected'>Rejected</span>";

}

else{

echo "<span class='status pending'>Pending</span>";

}


?>


</td>


<td>


<?php

if(!empty($row['qr_code'])){

echo "<img src='".$row['qr_code']."' width='100'>";

}
else{

echo "No QR";

}

?>


</td>


<td>

<?php echo $row['uploaded_at']; ?>

</td>


</tr>


<?php

}

}

else{

echo "<tr><td colspan='6'>No Documents Found</td></tr>";

}


?>


</table>


<br>


<a href="dashboard.php">

⬅ Back Dashboard

</a>


</div>

</div>


</body>

</html>