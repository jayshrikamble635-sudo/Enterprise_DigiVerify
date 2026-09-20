<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choose Document | DigiVerify</title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:420px;
    background:#1e293b;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 25px rgba(0,255,255,0.2);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

label{
    display:block;
    padding:12px;
    margin-bottom:10px;
    background:#111827;
    border-radius:10px;
    cursor:pointer;
}

label:hover{
    background:#0ea5e9;
    color:black;
}

input[type="radio"]{
    margin-right:10px;
}

/* Button container ke liye design */
.btn-group {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

button, .back-btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    font-size: 13.33px;
}

/* Back button design */
.back-btn {
    background: transparent;
    border: 2px solid #00d4ff;
    color: #00d4ff;
    box-sizing: border-box;
}

.back-btn:hover {
    background: rgba(0, 212, 255, 0.1);
}

/* Continue button design */
button[type="submit"]{
    background:#00d4ff;
    color: black;
}

button[type="submit"]:hover{
    background:#0ea5e9;
}
</style>

</head>
<body>

<div class="box">

<h2>Choose Document</h2>

<form action="upload.php" method="POST">

    <label>
        <input type="radio" name="document_type" value="aadhaar" required>
        Aadhaar Card
    </label>

    <label>
        <input type="radio" name="document_type" value="pan">
        PAN Card
    </label>

    <label>
        <input type="radio" name="document_type" value="passport">
        Passport
    </label>

    <label>
        <input type="radio" name="document_type" value="driving">
        Driving Licence
    </label>

    <label>
        <input type="radio" name="document_type" value="voter">
        Voter ID
    </label>

    <!-- Yahan par badlaav kiya gaya hai -->
    <div class="btn-group">
        <button type="button" onclick="history.back()" class="back-btn">Back</button>
        <button type="submit">Continue</button>
    </div>

</form>

</div>




</body>
</html>
