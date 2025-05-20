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
  $profile_pic=$tuple['profile_pic']
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
  </head>
  <body>
    <?php include 'components/header.php'; ?>

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
        <a href="add-recipe-view.php" class="btn-primary add-recipe-btn">+ Add New Recipe</a>
      </div>

      <div class="tabs">
        <input type="radio" id="tab1" name="tab" checked />
        <label for="tab1">My Recipes</label>

        <input type="radio" id="tab2" name="tab" />
        <label for="tab2">Saved Recipes</label>

        <div class="tab-content" id="content1">
          <div class="recipes-grid" id='resultado'>
          <?php
            $query = "SELECT * FROM recipes WHERE author = $1";
            $result = pg_query_params($dbconn, $query, [$username]);

            if (pg_num_rows($result) > 0) {
              while ($row = pg_fetch_assoc($result)) {
                $recipe = $row; 
                include 'components/recipe-card.php';
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
                  $recipe = $row; 
                  include 'components/recipe-card.php';
                }
              } else {
                echo "<p class=no-recipes>You haven't saved any recipes yet.</p>";
              }
            ?>
          </div>
          <div class="explore-more">
            <a href="explore.php" class="btn-secondary">Explore More Recipes</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
