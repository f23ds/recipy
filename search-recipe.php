<?php
session_start();

// Verificamos si el usuario está logueado
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied. Please log in.']);
    exit;
}

$username = $_SESSION['username'];

// Verificamos si se ha proporcionado el parámetro 'title'
if (!isset($_GET['title']) || empty(trim($_GET['title']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or empty title parameter.']);
    exit;
}

$title = trim($_GET['title']);

// Conectamos a la base de datos
$conn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456");

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error.']);
    exit;
}

// Preparamos y ejecutamos la consulta
$q1 = "SELECT * FROM recipes WHERE author != $1 AND title ILIKE '%' || $2 || '%'";
$result = pg_query_params($conn, $q1, array($username, $title));

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query execution failed.']);
    pg_close($conn);
    exit;
}

// Procesamos resultados
$recipes = [];

while ($row = pg_fetch_assoc($result)) {
    $row['ingredients'] = explode(',', trim($row['ingredients'], '{}'));

    // Verificamos si la receta está guardada por el usuario
    $checkSavedQuery = "SELECT 1 FROM saved_recipes WHERE username = $1 AND recipe_id = $2 LIMIT 1";
    $checkSavedResult = pg_query_params($conn, $checkSavedQuery, array($username, $row['id']));

    $row['saved'] = ($checkSavedResult && pg_num_rows($checkSavedResult) > 0);

    $recipes[] = $row;
}

// Devolvemos la respuesta en formato JSON
echo json_encode($recipes);

pg_close($conn);
?>
