<?php
session_start();

// Verificamos si el usuario está logueado
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Access denied. Please log in.";
    exit;
}

$username = $_SESSION['username'];

if (!isset($_SESSION['exploring_recipe_id'])) {
    echo "No recipe is selected.";
    exit;
}

$recipe_id=$_SESSION['exploring_recipe_id'];

// Conectamos a PostgreSQL
$conn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
        or die('Could not connect: ' . pg_last_error());


$query = "DELETE FROM recipes WHERE id = $1;";
$result = pg_query_params($conn, $query, [$recipe_id]);

if ($result && pg_affected_rows($result) === 1) {
    header("Location: dashboard.php");
} else {
    echo "Error: Could not delete recipe.";
}

pg_close($dbconn);
exit;

?>