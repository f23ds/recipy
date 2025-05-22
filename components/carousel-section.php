<?php
?>

<div class="carousel-wrapper">
  <button class="carousel-btn left"><i class="fas fa-chevron-left"></i></button>
  <div class="carousel-track" id="carousel-track">
    <?php
    if (pg_num_rows($result) > 0) {
      while ($row = pg_fetch_assoc($result)) {
        $recipe = $row;
        include 'recipe-card-explore-vue.php';
      }
    } else {
      echo '<p class="no-recipes">There are no recipes to explore.</p>';
    }
    ?>
    
  </div>
  <button class="carousel-btn right"><i class="fas fa-chevron-right"></i></button>
</div>
