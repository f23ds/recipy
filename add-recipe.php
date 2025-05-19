<?php
session_start();

// Validación básica
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

$username = $_SESSION['username'] ?? null;

if (!$username) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

$title = $_POST['title'];
$diners = $_POST['diners'];
$instructions = $_POST['instructions'] ?? '';
$ingredients_raw = $_POST['ingredients'] ?? '';
$ingredients_array = array_map('trim', explode(',', $ingredients_raw));
$ingredients_array = array_filter($ingredients_array);
$ingredients_pg = '{' . implode(',', $ingredients_array) . '}';

// Imagen
$destPath = 'img/recipe.png'; // valor por defecto

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];
    $fileType = $_FILES['image']['type'];
    // Opcional: extensión segura
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    // Ruta final
    $uploadDir = 'img/recipes/';
    $newFileName = uniqid('recipe_') . '.' . $ext;
    $destPath = $uploadDir . $newFileName;
    move_uploaded_file($fileTmpPath, $destPath);

}

$conn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456");

if (!$conn) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}

$query = "INSERT INTO recipes (title, diners, ingredients, instructions, author, image)
          VALUES ($1, $2, $3, $4, $5, $6)";
$params = [$title, $diners, $ingredients_pg, $instructions, $username, $destPath];

$result = pg_query_params($conn, $query, $params);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to insert recipe"]);
}
?>
