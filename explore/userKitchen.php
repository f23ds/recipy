<?php
  session_start();
  $username = $_SESSION['username'] ?? null;

  $active_user="";

  if (isset($_GET['user'])) {
    $active_user = $_GET['user'];
  }
  $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
          or die('Could not connect: ' . pg_last_error());

  $q0 = "SELECT * FROM users WHERE username = $1";
  $result = pg_query_params($dbconn, $q0, array($active_user));

  if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
      echo "<h1>Query error</h1>";
  }

  $name=$tuple['name'];
  $profile_pic=$tuple['profile_pic'];

  $q0 = "SELECT 
      r.*, 
      COUNT(s_all.recipe_id) AS times_saved,
      COUNT(s_user.recipe_id) > 0 AS is_saved
    FROM recipes r
    LEFT JOIN saved_recipes s_all ON r.id = s_all.recipe_id
    LEFT JOIN saved_recipes s_user ON r.id = s_user.recipe_id AND s_user.username = $1
    WHERE r.author = $1
    GROUP BY r.id;
  ";
  $result = pg_query_params($dbconn, $q0, array($active_user));

  if (pg_num_rows($result) === 0) {
    echo "<h1>No recipes found</h1>";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UserKitchen - Recipy</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/explore.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <script src="../js/explore.js"></script>
    <script src="https://unpkg.com/vue@3"></script>
  </head>
  <body>
    <?php include '../components/header.php'; ?>

    <div class="user-kitchen-container">
      <div class="user-kitchen-header">
        <div class="user-kitchen-info">
          <img
            src=<?php echo $profile_pic; ?>
            alt="User Avatar"
            class="user-kitchen-avatar"
          />
          <h1><?php echo $name; ?>’s Kitchen</h1>
        </div>
      </div>
    </div>

    <section class="explore-section">
      <?php
        include '../components/carousel-section.php';
      ?>
    </section>

    <?php include '../components/footer.php'; ?>
  </body>
</html>
