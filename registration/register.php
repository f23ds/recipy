<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro - Recipy</title>
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/auth.css" />
  <script src="validateForm.js"></script>
</head>
<body>
  <div class="background-shape shape1"></div>
  <div class="background-shape shape2"></div>

  <a href="../index.html" class="logo logo-login">recipy</a>

  <div class="login-container">
    <div class="login-box">
      <h2>Create an account</h2>
      <form action="registration.php" method="POST" name="myForm" onsubmit="return validateForm()">
        <input type="text" name="name" placeholder="Name">
        <p class="error" id="error-name"></p>
        <input type="text" name="username" placeholder="Username">
        <p class="error" id="error-surname"></p>
        <input type="email" name="email" placeholder="Email address">
        <p class="error" id="error-email"></p>
        <input type="password" name="password" placeholder="Password">
        <p class="error" id="error-password"></p>
        <input type="password" name="confirm_pw" placeholder="Confirm password">
        <p class="error" id="error-confirm_pw"></p>

        <?php
          if (isset($_GET['error'])) {
            $error = $_GET['error'];
            $mensaje = '';

            if ($error == 0)  {
              $mensaje = "❌ This username is currently in use. Try a different one.";
            } elseif ($error == 1) {
              $mensaje = "❌ This email address is currently in use. Try signing in.";
            } elseif ($error == 2) {
              $mensaje = "❌ Something went wrong. Please try again.";
            } 

            if ($mensaje !== '') {
              echo "<div class=\"accessing-error\">$mensaje</div>";
            }
          }
        ?>

        <button type="submit">Sign up</button>
      </form>
      <div class="extra-links">
        <p>Already have an account? <a href="../login/login_form.php">Log in</a></p>
        <a href="../index.html" class="back-home">← Back to home</a>
      </div>
    </div>
  </div>
</body>
</html>
