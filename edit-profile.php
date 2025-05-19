<?php

  session_start();
  $username = $_SESSION['username'] ?? null;
  $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
          or die('Could not connect: ' . pg_last_error());

  $q0 = "SELECT * FROM users WHERE username = $1";
  $result = pg_query_params($dbconn, $q0, array($username));

  if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
      echo "<h1>Query error</h1>";
  }

  $name=$tuple['name'];
  $email=$tuple['email'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile - Recipy</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/edit-profile.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
  </head>
  <body>
    <?php include 'components/header.php'; ?>

    <main class="edit-profile-container">
      <h1>Edit your profile</h1>

      <form class="edit-form" id="edit_profile">
        <div class="profile-image-section">
          <label for="profileImage" class="profile-img-label">
            <img id="profilePreview" src=<?php echo $tuple['profile_pic']?> alt="Profile Picture" />
            <div class="edit-icon">
              <i class="fas fa-pen"></i>
            </div>
          </label>
          <input type="file" name="profileImage" id="profileImage" accept="image/*" hidden />
        </div>

        <label for="username">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          value="<?php echo $username ?>"
          readonly
        />
        <p class="error" id="error-username"></p>

        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          name="email"
          value="<?php echo $email ?>"
          readonly
        />
        <p class="error" id="error-email"></p>

        <label for="password">New Password <span>(optional)</span></label>
        <input
          type="password"
          id="password"
          name="password"
          readonly
          placeholder="••••••••"
        />
        <p class="error" id="error"></p>

        <div class="form-actions">
          <button type="button" id="editBtn" class="btn-secondary">
            Edit All
          </button>
          <button type="submit" class="btn-primary" id=saveBtn>Save Changes</button>
          <a href="dashboard.php" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </main>

    <script src="edit_profile.js"></script>
  </body>
</html>
