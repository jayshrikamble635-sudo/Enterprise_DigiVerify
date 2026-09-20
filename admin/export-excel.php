<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location:login.php");
    exit();
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=DigiVerify_Report.xls");

echo "Reference ID\t";
echo "Email\t";
echo "Document\t";
echo "AI Score\t";
echo "Fraud Score\t";
echo "Recommendation\t";
echo "Status\t";
echo "Verified By\t";
echo "Verification Date\n";

$result = mysqli_query($conn,"
SELECT *
FROM documents
ORDER BY id DESC
");

while($row=mysqli_fetch_assoc($result)){

echo "DV".str_pad($row['id'],6,"0",STR_PAD_LEFT)."\t";

echo $row['email']."\t";

echo strtoupper($row['document_type'])."\t";

echo $row['ai_confidence']."%\t";

echo $row['fraud_score']."%\t";

echo $row['recommendation']."\t";

echo $row['status']."\t";

echo $row['verified_by']."\t";

echo $row['verified_at']."\n";

}
?>