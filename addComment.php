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

$input = json_decode(file_get_contents("php://input"), true);

$comment = $input['comment'];

$conn = pg_connect("host=localhost dbname=tsw user=postgres password=123456");
$query = "INSERT INTO comments (recipe_id, author, content) VALUES ($1, $2, $3)";
$params = [$recipe_id, $username, $comment];

$result = pg_query_params($conn, $query, $params);

if ($result) {
    echo json_encode(["success" => "Comment inserted correctly"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error when inserting the comment"]);
}

pg_close($conn);
?>
