<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
        or die('Could not connect: ' . pg_last_error());
}
if ($dbconn) {
    $email = $_POST['email'];
    $username = $_POST['username'];

    $q0 = "SELECT * FROM users WHERE username = $1";
    $result = pg_query_params($dbconn, $q0, array($username));

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        echo json_encode(["username" => "❌ This username is currently in use. Try a different one."]);
        exit;
    }

    session_start();
    $username = $_SESSION['username'] ?? null;

    // Verificar si el correo ya existe
    $q1 = "SELECT * FROM users WHERE email = $1 and username != $2";
    $result = pg_query_params($dbconn, $q1, array($email, $username));

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        echo json_encode(["email" => "❌ This email address is currently in use."]);
        exit;
    } else {
        $new_username = $_POST['username'];
        $email = $_POST['email'];
        $password=$_POST['password'];

        if ($password !== "") {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $q2 = "UPDATE users SET username = $1, email = $2, password = $3 WHERE username = $4;";
            $params = array($new_username, $email, $password, $username); 

        } else {
            $q2 = "UPDATE users SET username = $1, email = $2 WHERE username = $3;";
            $params = array($new_username, $email, $username); 
        }

        $data = pg_query_params($dbconn, $q2, $params);

        if ($data) {
            echo json_encode([
                "success" => true,
                "username" => $new_username,
                "email" => $email
            ]);
        } else {
            echo json_encode(["error" => "❌ Something went wrong. Please try again."]);
        }
    }

    pg_close($dbconn);
}
?>
