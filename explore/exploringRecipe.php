<<?php
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Access denied. Please log in.";
    exit;
}

$username = $_SESSION['username'];

if (!isset($_GET['exploring_recipe_id'])) {
    echo json_encode(false);
    exit;
}

$_SESSION['exploring_recipe_id'] = $_GET['exploring_recipe_id'];

echo json_encode(true);


?>