<div class="recipe-dashboard-card">
  <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
  <div class="card-info">
    <a href="recipe.php?recipe_id=<?php echo $id; ?>" class="card-title"><?php echo htmlspecialchars($title); ?></a>
    <span class="card-sub">@<?php echo htmlspecialchars($author); ?><?php if (isset($servings)) echo " • $servings servings"; ?></span>
  </div>
</div>
