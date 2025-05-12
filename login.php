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

    // Verificamos si el email existe
    $q1 = "SELECT * FROM utente WHERE email = $1";
    $result = pg_query_params($dbconn, $q1, array($email));

    if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
        header("Location: login.html");
        exit;
    } else {
        $password_input = $_POST['password'];
        $hashed_password_from_db = $tuple['password'];

        // Verificamos la contraseña
        if (!password_verify($password_input, $hashed_password_from_db)) {
            header("Location: login.html");
            exit;
        } else {
            $nome = $tuple['nome'];
            session_start();
            $_SESSION['usuario']=$nome;
            header("Location: dashboard.php");
            exit;
        }
    }

    pg_close($dbconn);
}
?>
