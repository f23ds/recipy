<?php
echo '<div class="recipe-dashboard-card" id="receta-' . $recipe['id'] . '">
        <img src="' . htmlspecialchars($recipe['image']) . '" alt="' . htmlspecialchars($recipe['title']) . '">
        <div class="card-info">
          <a href="../recipe/recipe.php?recipe_id=' . $recipe['id'] . '" class="card-title">' . htmlspecialchars($recipe['title']) . '</a>
          <span class="card-sub">@' . htmlspecialchars($recipe['author']) . ' • ' . $recipe['diners'] . ' diners  •  ' . $recipe['times_saved'] . ' likes</span>
        </div>
      </div>';
?>

