<?php
include("database/config.php");

$result = null;

if (isset($_POST['search'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM documents WHERE email='$email' ORDER BY id DESC LIMIT 1";

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Verification Status</title>
</head>
<body>

<h2>Check Document Status</h2>

<form method="POST">

    <input type="email" name="email" placeholder="Enter Email" required>

    <button type="submit" name="search">
        Check Status
    </button>

</form>

<?php if($result){ ?>

<hr>

<h3>Verification Result</h3>

<p><b>Email:</b> <?php echo $result['email']; ?></p>

<p><b>Document:</b> <?php echo ucfirst($result['document_type']); ?></p>

<p><b>Status:</b> <?php echo $result['status']; ?></p>

<p><b>File:</b> <?php echo $result['file_name']; ?></p>

<?php } ?>

</body>
</html>