<?php
session_start();

// Verificamos si el usuario está logueado
if (!isset($_SESSION['username']) || !isset($_SESSION['exploring_recipe_id'])) {
    http_response_code(403);
    echo "Access denied. Please log in.";
    exit;
}

$username = $_SESSION['username'];
$recipe_id = $_SESSION['exploring_recipe_id'];

if (!isset($_GET['code'])) {
    echo "No recipe is selected.";
    exit;
}

$code=$_GET['code'];

// Conectamos a PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
        or die('Could not connect: ' . pg_last_error());


if ($code==0) {
    $query = "DELETE FROM saved_recipes WHERE username = $1 AND recipe_id = $2;";
    $result = pg_query_params($conn, $query, [$username, $recipe_id]);
} elseif ($code==1) {
    $query = "INSERT INTO saved_recipes (username, recipe_id) VALUES ($1, $2)";
    $result = pg_query_params($conn, $query, [$username, $recipe_id]);
}

echo json_encode($result ? true : false);

?>
