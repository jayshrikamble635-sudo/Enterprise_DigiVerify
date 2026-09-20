<?php
session_start();
include("../database/config.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    // File ka naam nikalo
    $result = mysqli_query($conn, "SELECT file_name FROM documents WHERE id='$id'");

    if ($row = mysqli_fetch_assoc($result)) {

        $file = "../uploads/" . $row['file_name'];

        // Upload folder se file delete karo
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // Database se record delete karo
    mysqli_query($conn, "DELETE FROM documents WHERE id='$id'");
}

header("Location: documents.php");
exit();
?>