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
        echo "<h1>Spiacente, l'indirizzo email non è disponibile.</h1>
              Se vuoi, <a href='../login/index.html'>clicca qui per loggarti</a>";
    } else {
        $nome = $_POST['name'];
        $partes = explode(" ", $nome);
        $nome = $partes[0];
        $cognome = $partes[1];
        $cap = 11111;
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $q2 = "INSERT INTO utente (email, nome, cognome, password, cap) VALUES ($1, $2, $3, $4, $5)";
        $data = pg_query_params($dbconn, $q2, array($email, $nome, $cognome, $password, $cap));

        if ($data) {
            session_start();
            $_SESSION['usuario']=$nome;
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: registration.html");
            exit;
        }
    }

    pg_close($dbconn);
}
?>
