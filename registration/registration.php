<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
    exit;
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
        or die('Could not connect: ' . pg_last_error());
}
if ($dbconn) {
    $email = $_POST['email'];
    $username = $_POST['username'];

    if (trim($username) === '') {
        header("Location: register.php?error=0");
        exit;
    }

    $q0 = "SELECT * FROM users WHERE username = $1";
    $result = pg_query_params($dbconn, $q0, array($username));

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        header("Location: register.php?error=1");
        exit;
    }

    // Validación 3: Email vacío
    if (trim($email) === '') {
        header("Location: register.php?error=2");
        exit;
    }
    // Verificar si el correo ya existe
    $q1 = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($dbconn, $q1, array($email));

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        header("Location: register.php?error=3");
        exit;
    } else {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $confirm_pw = $_POST['confirm_pw'];
        
        // Validación 1: Nombre vacío
        if (trim($name) === '') {
            header("Location: register.php?error=4");
            exit;
        }

        // Validación 4: Contraseña vacía
        if (trim($password) === '') {
            header("Location: register.php?error=5");
            exit;
        }

        // Validación 6: Contraseñas no coinciden
        if ($password !== $confirm_pw) {
            header("Location: register.php?error=6");
            exit;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $q2 = "INSERT INTO users (name, username, email, password) VALUES ($1, $2, $3, $4)";
        $data = pg_query_params($dbconn, $q2, array($name, $username, $email, $password));

        if ($data) {
            session_start();
            $_SESSION['username']=$username;
            header("Location: ../dashboard.php");
            exit;
        } else {
            header("Location: register.php?error=7");
            exit;
        }
    }

    pg_close($dbconn);
}
?>
