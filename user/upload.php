<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Document</title>

<link rel="stylesheet" href="../css/dashboard.css">

<style>
.upload-box{
    width:500px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);
}
.upload-box input,
.upload-box select{
    width:100%;
    padding:10px;
    margin:10px 0;
}
.upload-box button{
    width:100%;
    padding:12px;
    background:#0d6efd;
    color:#fff;
    border:none;
    cursor:pointer;
    border-radius:5px;
}
</style>

</head>

<body>

<div class="sidebar">

<h2>DigiVerify</h2>

<a href="dashboard.php">Dashboard</a>
<a href="upload.php">Upload Document</a>
<a href="#">Verification Status</a>
<a href="#">Profile</a>
<a href="../index.php">Logout</a>

</div>

<div class="main">

<div class="topbar">
<h1>Upload Your Document</h1>
</div>

<div class="upload-box">

<h2>Document Verification</h2>

<form action="../upload_process.php" method="POST" enctype="multipart/form-data">

<label>Full Name</label>
<input type="text" name="fullname" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Select Document</label>

<select name="document_type" required>
<option value="">Select</option>
<option value="Aadhaar">Aadhaar Card</option>
<option value="PAN">PAN Card</option>
<option value="Passport">Passport</option>
<option value="Driving License">Driving License</option>
</select>

<label>Upload Document</label>
<input type="file" name="document" accept=".jpg,.jpeg,.png,.pdf" required>

<button type="submit">
Verify Document
</button>

</form>

</div>

</div>

</body>
</html>