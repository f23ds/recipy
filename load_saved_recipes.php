<?php
session_start();

// Verificamos si el usuario está logueado
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Access denied. Please log in.";
    exit;
}

$username = $_SESSION['username'];

// Conectamos a PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
        or die('Could not connect: ' . pg_last_error());

// Consulta: recetas creadas por el usuario
$query = "SELECT r.* FROM recipes r JOIN saved_recipes s ON r.id = s.recipe_id WHERE s.username = $1;";
$result = pg_query_params($conn, $query, [$username]);

$recetas = [];

while ($row = pg_fetch_assoc($result)) {
    $row['ingredients'] = explode(',', trim($row['ingredients'], '{}'));
    $recetas[] = $row;
}

// Devolver en formato JSON
echo json_encode($recetas);
?>
