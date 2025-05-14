<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión - Recipy</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/auth.css" />
    <script src="./rememberMe.js" type="application/javascript"></script>
  </head>
  <body>
    <div class="background-shape shape1"></div>
    <div class="background-shape shape2"></div>

    <a href="../index.html" class="logo logo-login">recipy</a>

    <div class="login-container">
      <div class="login-box">
        <h2>Sign in</h2>
        <form action="login.php" method="POST" onsubmit="return alertRmb()">
          <input
            type="email"
            name="email"
            placeholder="Email address"
          />
          <p class="error" id="error-email"></p>
          <input
            type="password"
            name="password"
            placeholder="Password"
          />
          <p class="error" id="error-password"></p>

          <div class="remember-me">
            <input type="checkbox" id="remember" name="remember" />
            <label for="remember">Remember me</label>
          </div>

          <?php
            if (isset($_GET['error'])) {
              $error = $_GET['error'];
              $mensaje = '';

              if ($error == 0)  {
                $mensaje = "❌ This email is not registered. Try signing up.";
              } elseif ($error == 1) {
                $mensaje = "❌ Incorrect password. Try again.";
              } 

              if ($mensaje !== '') {
                echo "<div class=\"accessing-error\">$mensaje</div>";
              }
            }
          ?>

          <button type="submit">Entrar</button>
        </form>

        <div class="extra-links">
          <p>Don't have an account? <a href="../registration/register.php">Sign up</a></p>
          <a href="../index.html" class="back-home">← Back to home</a>
        </div>
      </div>
    </div>
  </body>
</html>
