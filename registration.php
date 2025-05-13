<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
    exit;
} else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=EsempioLogin user=postgres password=123456")
        or die('Could not connect: ' . pg_last_error());
}
if ($dbconn) {
    $email = $_POST['email'];

    // Verificar si el correo ya existe
    $q1 = "SELECT * FROM utente WHERE email = $1";
    $result = pg_query_params($dbconn, $q1, array($email));

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        header("Location: register.php?error=1");
        exit;
    } else {
        $nome = $_POST['name'];
        $cap = 11111;
        $password = $_POST['password'];
        $confirm_pw = $_POST['confirm_pw'];
        
        // Validación 1: Nombre vacío
        if (trim($nome) === '') {
            header("Location: register.php?error=2");
            exit;
        }

        // Validación 2: Nombre sin espacio (sin apellido)
        if (strpos($nome, ' ') === false) {
            header("Location: register.php?error=2");
            exit;
        }

        // Validación 3: Email vacío
        if (trim($email) === '') {
            header("Location: register.php?error=5");
            exit;
        }

        // Validación 4: Contraseña vacía
        if (trim($password) === '') {
            header("Location: register.php?error=3");
            exit;
        }

        // Validación 6: Contraseñas no coinciden
        if ($password !== $confirm_pw) {
            header("Location: register.php?error=4");
            exit;
        }

        $partes = explode(" ", $nome);
        $nome = $partes[0];
        $cognome = $partes[1];

        $password = password_hash($password, PASSWORD_DEFAULT);

        $q2 = "INSERT INTO utente (email, nome, cognome, password, cap) VALUES ($1, $2, $3, $4, $5)";
        $data = pg_query_params($dbconn, $q2, array($email, $nome, $cognome, $password, $cap));

        if ($data) {
            session_start();
            $_SESSION['usuario']=$nome;
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: register.php?error=5");
            exit;
        }
    }

    pg_close($dbconn);
}
?>
