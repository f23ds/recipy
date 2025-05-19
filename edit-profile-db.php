<?php
session_start();
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

    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC) && $username!==$_SESSION['username']) {
        echo json_encode(["username" => "❌ This username is currently in use. Try a different one."]);
        exit;
    }

    $username = $_SESSION['username'] ?? null;

    $destPath="img/user.png";

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profileImage']['tmp_name'];
        $fileName = $_FILES['profileImage']['name'];
        $fileSize = $_FILES['profileImage']['size'];
        $fileType = $_FILES['profileImage']['type'];

        // Opcional: extensión segura
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        // Ruta final
        $uploadDir = 'img/profiles/';
        $newFileName = $_POST['username'] . '.' . $ext;
        $destPath = $uploadDir . $newFileName;

        move_uploaded_file($fileTmpPath, $destPath);

    }

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

            $q2 = "UPDATE users SET username = $1, email = $2, profile_pic = $5, password = $3 WHERE username = $4;";
            $params = array($new_username, $email, $password, $username, $destPath); 

        } else {
            $q2 = "UPDATE users SET username = $1, email = $2, profile_pic = $4 WHERE username = $3;";
            $params = array($new_username, $email, $username, $destPath); 
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
