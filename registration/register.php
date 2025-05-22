<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Recipy</title>
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/auth.css" />
  <script src="../js/validateForm.js"></script>
</head>
<body>
  <div class="background-shape shape1"></div>
  <div class="background-shape shape2"></div>

  <a href="../index.php" class="logo logo-login">recipy</a>

  <div class="login-container">
    <div class="login-box">
      <h2>Create an account</h2>
      <form name=myForm id="register_form">
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

        <div class="accessing-error" id="accessing-error"></div>

        <button type="submit">Sign up</button>
      </form>
      <div class="extra-links">
        <p>Already have an account? <a href="../login/login_form.php">Log in</a></p>
        <a href="../index.php" class="back-home">← Back to home</a>
      </div>
    </div>
  </div>
</body>
</html>
