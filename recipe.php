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
  $username = $_SESSION['username'] ?? null;

  $title=$tuple['title'];
  $diners=$tuple['diners'];
  $ingredientes_raw = $tuple['ingredients'];
  $ingredientes = explode(',', trim($ingredientes_raw, '{}'));
  $ingredientes = array_map(fn($item) => trim($item, ' "'), $ingredientes);
  $instructions_raw = trim($tuple['instructions'], '{}');
  $instructions=str_getcsv($instructions_raw, ',', '"');
  $instructions = array_filter($instructions, fn($s) => trim($s) !== '');
  $instructions = array_values($instructions);
  $author=$tuple['author'];
  $descr=$tuple['descr'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receta - Recipy</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/recipe.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap"
      rel="stylesheet"
    />
    <script src="load_dashboard.js"></script>
  </head>
  <body>
    <?php include 'components/header.php'; ?>

    <main class="recipe-container">
      <div class="recipe-header">
        <img src=<?php echo $tuple['image']?> alt="Recipe Image" class="recipe-image" />

        <div class="recipe-header-content">
          <div>
            <h1 class="recipe-title"><?php echo "$title - $diners diners"; ?>
              <?php if ($username === $author): ?>
                <i class="fa-solid fa-trash delete-icon" id="deleteBtn"></i>
              <?php endif; ?>
            </h1>
            <?php
              echo "<p class=\"recipe-author\">
                      by <a href=\"userKitchen.php?user=$author\" class=\"author-name-link\">@$author</a>
                    </p>";
            ?>
          </div>

          <?php
            $query = "SELECT * FROM recipes WHERE author = $1 AND id = $2;";
            $result1 = pg_query_params($dbconn, $query, [$username, $id]);

            if (pg_num_rows($result1)==0) {
              echo '
                    <button class="btn-like" aria-label="Save Recipe">
                      <i class="fa-regular fa-heart"></i>
                    </button>';
            }
          ?>
        </div>
      </div>

      <section class="recipe-content">
        <p class="recipe-description">
          <?php
            echo $descr;
          ?>
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
            foreach ($instructions as $step) {
              echo "<li>" . htmlspecialchars($step) . "</li>";
            }
          ?>
        </ol>
      </section>

      <section class="comments">
        <div class="comment" id=comments>
        </div>
      </section>
    </main>

    <div class="modal-overlay" id="delete-modal">
      <div class="modal-content">
        <h3>Are you sure you want to delete this recipe?</h3>
        <div class="modal-buttons">
          <button class="btn-cancel" id="cancel-delete">Cancel</button>
          <a href="delete.php" class="btn-confirm">Yes, delete</a>
        </div>
      </div>
    </div>
    <script>
      const deleteBtn = document.getElementById('deleteBtn');
      const modal = document.getElementById('delete-modal');
      const cancelBtn = document.getElementById('cancel-delete');
            
      if (deleteBtn && modal && cancelBtn) {
        deleteBtn.addEventListener('click', () => {
          modal.classList.add('active');
        });
      
        cancelBtn.addEventListener('click', () => {
          modal.classList.remove('active');
        });
      
        window.addEventListener('click', (e) => {
          if (e.target === modal) {
            modal.classList.remove('active');
          }
        });
      }
    </script>

  </body>
</html>
