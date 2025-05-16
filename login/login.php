<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

$dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
    or die(json_encode(["error" => "Database connection failed."]));

$email = $_POST['email'] ?? '';
$password_input = $_POST['password'] ?? '';

// Verificamos si el email existe
$q1 = "SELECT * FROM users WHERE email = $1";
$result = pg_query_params($dbconn, $q1, [$email]);

if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
    echo json_encode(["email" => "❌ This email is not registered. Try signing up."]);
    exit;
}

$hashed_password_from_db = $tuple['password'];

if (!password_verify($password_input, $hashed_password_from_db)) {
    echo json_encode(["password" => "❌ Incorrect password. Try again."]);
    exit;
}

session_start();
$_SESSION['username'] = $tuple['username'];

echo json_encode([
    "success" => true,
    "redirect" => "../dashboard.php"
]);

pg_close($dbconn);
exit;
?>
