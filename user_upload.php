<?php
session_start();
include("database/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$message="";

if(isset($_POST['upload']))
{

$document_type=mysqli_real_escape_string($conn,$_POST['document_type']);

$file=$_FILES['document']['name'];
$tmp=$_FILES['document']['tmp_name'];

$newname=time()."_".basename($file);

$folder="uploads/";

if(!file_exists($folder))
{
mkdir($folder);
}

if(move_uploaded_file($tmp,$folder.$newname))
{

mysqli_query($conn,"
INSERT INTO documents
(
user_id,
document_type,
file_name,
quality,
fraud_score,
ai_confidence,
result,
recommendation,
status
)
VALUES
(
'$user_id',
'$document_type',
'$newname',
'Good',
0,
95,
'Pending Review',
'Needs Review',
'Pending'
)
");

$message="<div class='success'>✅ Document Uploaded Successfully.</div>";

}
else
{
$message="<div class='error'>❌ Upload Failed.</div>";
}

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Upload | DigiVerify</title>

<link rel="stylesheet" href="css/user.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<div class="sidebar">

<h2>DigiVerify</h2>

<a href="upload.php">
📤 Upload Document
</a>

<a href="my_documents.php">
📄 My Documents
</a>

<a href="verification_status.php">
✅ Verification Status
</a>

<a href="user_notifications.php">
🔔 Notifications
</a>

<a href="user_profile.php">
👤 My Profile
</a>

<a href="user_settings.php">
⚙️ Settings
</a>
</div>

<div class="main">

<div class="topbar">

<h2>📤 Upload Document</h2>

<p>Upload your document securely for AI verification.</p>

</div>

<?php echo $message; ?>

<div class="upload-card">

<form method="POST" enctype="multipart/form-data">

<div class="form-group">

<label>Document Type</label>

<select name="document_type" required>

<option value="">Select Document</option>

<option value="Aadhaar">Aadhaar Card</option>

<option value="PAN">PAN Card</option>

<option value="Passport">Passport</option>

<option value="Driving License">Driving License</option>

</select>

</div>

<div class="form-group">

<label>Select File</label>

<input
type="file"
name="document"
accept=".jpg,.jpeg,.png,.pdf"
required>

</div>

<button
type="submit"
name="upload"
class="upload-btn">

<i class="fa-solid fa-cloud-arrow-up"></i>

Upload Document

</button>

</form>

</div>

<div class="quick-actions">

<div class="quick-card">

📄

<h3>Supported Files</h3>

<p>JPG, PNG, PDF</p>

</div>

<div class="quick-card">

🤖

<h3>AI Verification</h3>

<p>Automatic Analysis</p>

</div>

<div class="quick-card">

🔒

<h3>Secure Storage</h3>

<p>Encrypted Documents</p>

</div>

<div class="quick-card">

⚡

<h3>Fast Processing</h3>

<p>Instant Upload</p>

</div>

</div>

</div>

</body>

</html>