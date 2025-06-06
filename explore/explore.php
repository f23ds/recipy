<?php
session_start();
$username = $_SESSION['username'] ?? null;
$dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
  or die('Could not connect: ' . pg_last_error());

$q0 = "SELECT 
    r.*, 
    COUNT(s_all.recipe_id) AS times_saved,
    COUNT(s_user.recipe_id) > 0 AS is_saved
  FROM recipes r
  LEFT JOIN saved_recipes s_all ON r.id = s_all.recipe_id
  LEFT JOIN saved_recipes s_user ON r.id = s_user.recipe_id AND s_user.username = $1
  WHERE r.author != $1
  GROUP BY r.id;
";
$result = pg_query_params($dbconn, $q0, array($username));

if (pg_num_rows($result) === 0) {
    echo "<h1>No recipes found</h1>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Explore Recipes - Recipy</title>
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/explore.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="../js/explore.js" type="application/javascript"></script>
  <script src="https://unpkg.com/vue@3"></script>
</head>

<body>
  <?php include '../components/header.php'; ?>

  <!-- Search -->
  <?php include '../components/search-bar.php'; ?>

  <!-- Carousel -->
  <section class="explore-section">
    <h2>Trending Recipes</h2>
    <?php
    include '../components/carousel-section.php';
    ?>
  </section>
</body>

<?php include '../components/footer.php'; ?>

</html>