<?php
?>

<div class="carousel-wrapper">
  <button class="carousel-btn left"><i class="fas fa-chevron-left"></i></button>
  <div class="carousel-track" id="carousel-track">
    <?php
    if (pg_num_rows($result) > 0) {
      while ($row = pg_fetch_assoc($result)) {
        echo '<div class="recipe-card">';
        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Recipe" />';
        echo '<div class="recipe-info">';
        echo '<a class="recipe-title" href="recipe.php?recipe_id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a>';
        echo '<span class="recipe-user"><a href="userKitchen.php?user='.$row['author'].'">@' . htmlspecialchars($row['author']) . '</a><span class="diners-count"> • '.$row['diners'].' diners</span></span>';
        $query = "SELECT * FROM saved_recipes WHERE username = $1 AND recipe_id = $2;";
        $result1 = pg_query_params($dbconn, $query, [$username, $row['id']]);
        if ($result1 && pg_num_rows($result1) > 0) {
          echo '<button class="like-btn liked" onclick="saveRecipe(' . $row['id'] . ')"><i class="fa-solid fa-heart"></i></button>';
        } else {
          echo '<button class="like-btn" onclick="saveRecipe(' . $row['id'] . ')"><i class="fa-regular fa-heart"></i></button>';
        }
        echo '</div>';
        echo '</div>';
      }
    } else {
      echo '<p class="no-recipes">There are no recipes to explore.</p>';
    }
    ?>
  </div>
  <button class="carousel-btn right"><i class="fas fa-chevron-right"></i></button>
</div>
