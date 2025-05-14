<?php
  if (isset($_GET['recipe_id'])) {
    $id = $_GET['recipe_id'];
  }
  $dbconn = pg_connect("host=localhost port=5432 dbname=tsw user=postgres password=123456")
          or die('Could not connect: ' . pg_last_error());

  $q0 = "SELECT * FROM recipes WHERE id = $1";
  $result = pg_query_params($dbconn, $q0, array($id));

  if (!($tuple = pg_fetch_array($result, null, PGSQL_ASSOC))) {
      echo "<h1>Query error</h1>";
  }

  session_start();
  $_SESSION['exploring_recipe_id']=$id;

  $title=$tuple['title'];
  $diners=$tuple['diners'];
  $ingredientes_raw = $tuple['ingredients'];
  $ingredientes = explode(',', trim($ingredientes_raw, '{}'));
  $instructions=$tuple['instructions'];
  $author=$tuple['author'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receta - Recipy</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/recipe.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap"
      rel="stylesheet"
    />
    <script src="load_authors_recipes.js"></script>
  </head>
  <body>
    <nav class="navbar">
      <div class="logo">recipy</div>
      <ul class="nav-links">
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Explore</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Logout</a></li>
      </ul>
    </nav>

    <main class="recipe-container">
      <div class="recipe-header">
        <img src="img/recipe.png" alt="Recipe Image" class="recipe-image" />

        <div class="recipe-header-content">
          <div>
            <h1 class="recipe-title"><?php echo $title; ?></h1>
            <?php
              echo "<p class=\"recipe-author\">
                      by <a href=\"profile.php?user=laura_kitchen\" class=\"author-name-link\">@$author</a>
                    </p>"
            ?>
          </div>

          <button class="btn-like" aria-label="Save Recipe">
            <i class="fa-regular fa-heart"></i>
          </button>
        </div>
      </div>

      <section class="recipe-content">
        <p class="recipe-description">
          A classic Roman pasta dish made with eggs, cheese, pancetta, and
          pepper. Quick, creamy, and comforting.
        </p>

        <h2>Ingredients</h2>
        <?php
          echo '<ul class="ingredients">';
          foreach ($ingredientes as $item) {
              echo "<li>$item</li>";
          }
          echo '</ul>';
        ?>

        <h2>Steps</h2>
        <ol class="steps">
          <?php
            echo "<li>$instructions</li>";
          ?>
        </ol>
      </section>

      <section class="comments">
        <h3>Comments</h3>
        <div class="comment">
          <strong>@foodlover99:</strong> This was amazing!
        </div>
        <div class="comment">
          <strong>@veganchef:</strong> I used tofu instead of pancetta —
          perfect!
        </div>

        <form class="comment-form">
          <input type="text" placeholder="Write a comment..." required />
          <button type="submit">Post</button>
        </form>
      </section>
    </main>
  </body>
</html>
