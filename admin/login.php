<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../database/config.php");

// Already Logged In
if(isset($_SESSION['admin_id'])){
    header("Location: dashboard.php");
    exit();
}

// Login Process
if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    $sql = "SELECT * FROM admins
            WHERE email='$email'
            AND password='$password'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if(!$result){
        die("SQL Error: ".mysqli_error($conn));
    }

    if(mysqli_num_rows($result) == 1){

        $admin = mysqli_fetch_assoc($result);

        $_SESSION['admin_id']    = $admin['id'];
        $_SESSION['admin_name']  = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role']  = $admin['role'];

        header("Location: dashboard.php");
        exit();

    }else{

        $error = "Invalid Email or Password";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Enterprise Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Segoe UI,Arial,sans-serif;
}

.card{
    width:500px;
    border:none;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 0 35px rgba(0,212,255,.30);
}

.card-header{
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    color:#fff;
    text-align:center;
    padding:20px;
}

.card-body{
    padding:35px;
}

.btn-primary{
    background:#06b6d4;
    border:none;
    font-weight:bold;
}

.btn-primary:hover{
    background:#0891b2;
}

</style>

</head>

<body>

<div class="card">

<div class="card-header">

<h2>🔐 Enterprise Admin Login</h2>

</div>

<div class="card-body">

<?php
if(isset($error)){
?>
<div class="alert alert-danger">
<?php echo $error; ?>
</div>
<?php
}
?>

<form method="POST" autocomplete="off">

<div class="mb-3">

<label class="form-label">Admin Email</label>

<input
type="email"
name="email"
class="form-control"
placeholder="Enter Admin Email"
autocomplete="off"
required>

</div>

<div class="mb-4">

<label class="form-label">Password</label>

<input
type="password"
name="password"
class="form-control"
placeholder="Enter Password"
autocomplete="new-password"
required>

</div>

<button
type="submit"
name="login"
class="btn btn-primary w-100">

Login to Dashboard

</button>
<!-- Admin Back to Home Link -->
<div style="text-align: center; margin-top: 20px;">
    <a href="../index.php" style="text-align: center; color: #00b4d8; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-block; transition: 0.3s;">
        ← Back to Home
    </a>
</div>


</form>

</div>

</div>

</body>
</html>