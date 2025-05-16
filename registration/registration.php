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

    // Verificar si el correo ya existe
    $q1 = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($dbconn, $q1, array($email));

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        echo json_encode(["email" => "❌ This email address is currently in use. Try signing in."]);
        exit;
    } else {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $confirm_pw = $_POST['confirm_pw'];

        $password = password_hash($password, PASSWORD_DEFAULT);

        $q2 = "INSERT INTO users (name, username, email, password) VALUES ($1, $2, $3, $4)";
        $data = pg_query_params($dbconn, $q2, array($name, $username, $email, $password));

        if ($data) {
            session_start();
            $_SESSION['username']=$username;
            echo json_encode([
                "success" => true,
                "redirect" => "../dashboard.php"
            ]);
        } else {
            echo json_encode(["error" => "❌ Something went wrong. Please try again."]);
            exit;
        }
    }

    pg_close($dbconn);
}
?>
