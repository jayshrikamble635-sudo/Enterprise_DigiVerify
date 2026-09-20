<?php

header("Content-Type: application/json");

echo json_encode([
    "status" => "success",
    "message" => "Enterprise DigiVerify API Running"
]);