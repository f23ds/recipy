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

  $name=$tuple['name']
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Recipy</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <script
      src="https://kit.fontawesome.com/yourkit.js"
      crossorigin="anonymous"
    ></script>
    <script src="load_dashboard.js"></script>
    <!-- Solo si usas iconos -->
  </head>
  <body>
    <nav class="navbar">
      <div class="logo">recipy</div>
      <ul class="nav-links">
        <li><a href="#">Dashboard</a></li>
        <li><a href="explore.html">Explore</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#logout">Logout</a></li>
      </ul>
    </nav>

    <!-- Logout Modal -->
    <div class="modal-overlay" id="logout-modal">
      <div class="modal-content">
        <h3>Are you sure you want to logout?</h3>
        <div class="modal-buttons">
          <a href="index.html" class="btn-confirm">Yes, logout</a>
          <button class="btn-cancel" id="cancel-logout">Cancel</button>
        </div>
      </div>
    </div>

    <div class="dashboard-container">
      <div class="dashboard-header">
        <div class="user-info">
          <img
            src="img/user.png"
            alt="User Avatar"
            class="user-avatar"
          />
          <h1><?php echo $name; ?>’s Kitchen</h1>
        </div>
        <a href="#" class="btn-primary add-recipe-btn">+ Add New Recipe</a>
      </div>

      <div class="tabs">
        <input type="radio" id="tab1" name="tab" checked />
        <label for="tab1">My Recipes</label>

        <input type="radio" id="tab2" name="tab" />
        <label for="tab2">Saved Recipes</label>

        <div class="tab-content" id="content1">
          <div class="recipes-grid" id='resultado'>
          <?php
            $query = "SELECT id, title, diners, ingredients, instructions FROM recipes WHERE author = $1";
            $result = pg_query_params($dbconn, $query, [$username]);

            if (pg_num_rows($result) > 0) {
              while ($row = pg_fetch_assoc($result)) {
                echo '<div class="recipe-card" id="receta-' . $row['id'] . '">';
                echo '<a href="recipe.php?recipe_id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a>';
                echo '</div>';
              }
            } else {
              echo "<p class=no-recipes>You haven't added any recipes yet.</p>";
            }
          ?>
          </div>
        </div>

        <div class="tab-content" id="content2">
          <div class="recipes-grid" id='resultado2'>
            <?php
              $query = "SELECT r.* FROM recipes r JOIN saved_recipes s ON r.id = s.recipe_id WHERE s.username = $1;";
              $result = pg_query_params($dbconn, $query, [$username]);


              if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                  echo '<div class="recipe-card" id="receta-' . $row['id'] . '">';
                  echo '<a href="recipe.php?recipe_id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a>';
                  echo '</div>';
                }
              } else {
                echo "<p class=no-recipes>You haven't saved any recipes yet.</p>";
              }
            ?>
          </div>
          <div class="explore-more">
            <a href="#" class="btn-secondary">Explore More Recipes</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
