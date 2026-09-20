<?php

header("Content-Type: application/json");

include "config/database.php";

echo json_encode([
    "status" => true,
    "message" => "Database Connected Successfully"
]);