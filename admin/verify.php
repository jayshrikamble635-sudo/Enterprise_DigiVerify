<?php

include("../database/config.php");

$query = "SELECT * FROM documents ORDER BY uploaded_at DESC";

$result = mysqli_query($conn,$query);

?>

<table class="admin-table">

<tr>
    <th>ID</th>
    <th>Email</th>
    <th>Document Type</th>
    <th>File</th>
    <th>OCR Text</th>
    <th>Status</th>
    <th>Action</th>
</tr>


<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td>
<?= $row['id']; ?>
</td>


<td>
<?= $row['email']; ?>
</td>


<td>
<?= $row['document_type']; ?>
</td>


<td>
<a href="../<?= $row['file_path']; ?>" target="_blank">
View
</a>
</td>


<td>

<?= substr($row['ocr_text'],0,80); ?>

</td>


<td>

<?php

$status = $row['verification_status'];

if($status=="Approved")
{
    echo "<span class='approved'>Approved</span>";
}
elseif($status=="Rejected")
{
    echo "<span class='rejected'>Rejected</span>";
}
else
{
    echo "<span class='pending'>Pending</span>";
}

?>

</td>


<td>

<a href="verify.php?id=<?= $row['id']; ?>&status=Approved">
Approve
</a>


<a href="verify.php?id=<?= $row['id']; ?>&status=Rejected">
Reject
</a>


</td>


</tr>

<?php } ?>


</table>