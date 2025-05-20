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
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/explore.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="./load_dashboard.js" type="application/javascript"></script>
</head>

<body>
  <?php include 'components/header.php'; ?>

  <!-- Search -->
  <div class="search-bar">
    <input type="text" class="search-input" id="search-input" placeholder="Search recipes..." />
    <i class="fas fa-search" id="search-btn"></i>
  </div>

  <!-- Carousel -->
  <section class="explore-section">
    <h2>Trending Recipes</h2>
    <?php
    include 'components/carousel-section.php';
    ?>
  </section>
</body>

</html>