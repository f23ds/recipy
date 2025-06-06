<?php
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied. Please log in.']);
    exit;
}

$username = $_SESSION['username'];

if (!isset($_GET['title']) || empty(trim($_GET['title']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or empty title parameter.']);
    exit;
}

$title = trim($_GET['title']);

$conn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456");

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error.']);
    exit;
}

$q1 = "SELECT * FROM recipes WHERE author != $1 AND title ILIKE '%' || $2 || '%'";
$result = pg_query_params($conn, $q1, array($username, $title));

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query execution failed.']);
    pg_close($conn);
    exit;
}

$recipes = [];

while ($row = pg_fetch_assoc($result)) {
    $row['ingredients'] = explode(',', trim($row['ingredients'], '{}'));

    $checkSavedQuery = "SELECT 1 FROM saved_recipes WHERE username = $1 AND recipe_id = $2 LIMIT 1";
    $checkSavedResult = pg_query_params($conn, $checkSavedQuery, array($username, $row['id']));

    $row['saved'] = ($checkSavedResult && pg_num_rows($checkSavedResult) > 0);

    $likesQ = "SELECT COUNT(*) AS times_saved FROM saved_recipes WHERE recipe_id = $1;";
    $likesRes = pg_query_params($conn, $likesQ, [$row['id']]);
    $row['times_saved'] = pg_fetch_assoc($likesRes)['times_saved'];

    $recipes[] = $row;
}

echo json_encode($recipes);

pg_close($conn);
?>
