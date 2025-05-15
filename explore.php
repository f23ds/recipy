<?php
  session_start();
  $username = $_SESSION['username'] ?? null;
  $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
          or die('Could not connect: ' . pg_last_error());

  $q0 = "SELECT * FROM recipes WHERE author != $1";
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
  <title>Explore Recipes - Recipy</title>
  <link rel="stylesheet" href="css/explore.css" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="load_dashboard.js"></script>
</head>

<body>
  <!-- Header -->
  <nav class="navbar">
    <div class="logo">recipy</div>
    <ul class="nav-links">
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">Explore</a></li>
      <li><a href="#">Profile</a></li>
      <li><a href="#">Logout</a></li>
    </ul>
  </nav>

  <!-- Search -->
  <div class="explore-header">
    <input type="text" class="search-input" placeholder="Search recipes..." />
    <div class="tag-filter">
      <button>#vegan</button>
      <button>#dessert</button>
      <button>#quick</button>
      <button>#pasta</button>
    </div>
  </div>

  <!-- Carousel -->
  <section class="explore-section">
    <h2>Trending Recipes</h2>
    <div class="carousel-wrapper">
      <button class="carousel-btn left"><i class="fas fa-chevron-left"></i></button>

      <div class="carousel-track">
        <!-- Puedes reemplazar esto por includes de PHP -->
        <?php
          if (pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
              echo '<div class="recipe-card">';
              echo '<img src="img/recipe.png" alt="Recipe" />';
              echo '<div class="recipe-info">';
              echo '<a class="recipe-title" href="recipe.php?recipe_id=' . $row['id'] . '">'. htmlspecialchars($row['title']) .'</a>';
              echo '<span class="recipe-user"><a href="#">@'. $row['author'] .'</a></span>';
              echo '<button class="like-btn" onclick=saveRecipy('.$row['id'].')><i class="fa-regular fa-heart"></i></button>';
              echo '</div>';
              echo '</div>';
            }
          } else {
            echo "<p class=no-recipes>There are no recipes to explore.</p>";
          }
        ?>

      <button class="carousel-btn right"><i class="fas fa-chevron-right"></i></button>
    </div>
  </section>
</body>

</html>
