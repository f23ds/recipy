<?php
  session_start();
  $active_user = $_SESSION['username'] ?? null;

  $username="";

  if (isset($_GET['user'])) {
    $username = $_GET['user'];
  }
  $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
          or die('Could not connect: ' . pg_last_error());

  $q0 = "SELECT * FROM users WHERE username = $1";
  $result = pg_query_params($dbconn, $q0, array($username));

  if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
      echo "<h1>Query error</h1>";
  }

  $name=$tuple['name'];
  $profile_pic=$tuple['profile_pic'];

  $q0 = "SELECT * FROM recipes WHERE author = $1";
  $result = pg_query_params($dbconn, $q0, array($username));

  if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
      echo "<h1>Query error</h1>";
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Recipy</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/explore.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <script
      src="https://kit.fontawesome.com/yourkit.js"
      crossorigin="anonymous"
    ></script>
    <script src="load_dashboard.js"></script>
  </head>
  <body>
    <nav class="navbar">
      <div class="logo">recipy</div>
      <ul class="nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="explore.php">Explore</a></li>
        <li><a href="edit-profile.php">Profile</a></li>
        <li><a href="#logout">Logout</a></li>
      </ul>
    </nav>

    <!-- Logout Modal -->
    <div class="modal-overlay" id="logout-modal">
      <div class="modal-content">
        <h3>Are you sure you want to logout?</h3>
        <div class="modal-buttons">
          <a href="logout.php" class="btn-confirm">Yes, logout</a>
          <button class="btn-cancel" id="cancel-logout">Cancel</button>
        </div>
      </div>
    </div>

    <div class="dashboard-container">
      <div class="dashboard-header">
        <div class="user-info">
          <img
            src=<?php echo $profile_pic; ?>
            alt="User Avatar"
            class="user-avatar"
          />
          <h1><?php echo $name; ?>’s Kitchen</h1>
        </div>
      </div>

    <section class="explore-section">
      <div class="carousel-wrapper">
        <button class="carousel-btn left"><i class="fas fa-chevron-left"></i></button>

        <div class="carousel-track" id="carousel-track">
          <!-- Puedes reemplazar esto por includes de PHP -->
          <?php
            if (pg_num_rows($result) > 0) {
              while ($row = pg_fetch_assoc($result)) {
                echo '<div class="recipe-card">';
                echo '<img src='.$row['image'].' alt="Recipe" />';
                echo '<div class="recipe-info">';
                echo '<a class="recipe-title" href="recipe.php?recipe_id=' . $row['id'] . '">'. htmlspecialchars($row['title']) .'</a>';
                echo '<span class="recipe-user"><a href="#">@'. $row['author'] .'</a></span>';
                $query = "SELECT * FROM saved_recipes WHERE username = $1 AND recipe_id = $2;";
                $result1 = pg_query_params($dbconn, $query, [$active_user, $row['id']]);

                if ($result1 && pg_num_rows($result1) > 0) {
                  echo '<button class="like-btn liked" onclick=saveRecipe('.$row['id'].')><i class="fa-solid fa-heart"></i></button>';
                } else {
                  echo '<button class="like-btn" onclick=saveRecipe('.$row['id'].')><i class="fa-regular fa-heart"></i></button>';
                }
                echo '</div>';
                echo '</div>';
              }
            } else {
              echo "<p class=no-recipes>There are no recipes to explore.</p>";
            }
          ?>
      </div>

      <button class="carousel-btn right"><i class="fas fa-chevron-right"></i></button>
    </div>
  </section>
  </body>
</html>
