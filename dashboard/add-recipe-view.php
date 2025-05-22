<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Recipe - Recipy</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/add-recipe.css" />
     <link rel="stylesheet" href="../css/auth.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  </head>
  <body>
    <?php include '../components/header.php'; ?>

    <main class="add-recipe-container">
      <h1>Add a New Recipe</h1>

      <form class="add-recipe-form" name="myForm" id="add_recipe">
        <label for="title">Recipe Title</label>
        <input type="text" id="title" name="title" placeholder="e.g. Spaghetti Carbonara"/>
        <p class="error" id="error-title"></p>

        <label for="Diners">Diners</label>
        <input type="number" id="diners" name="diners" min="1" placeholder="e.g. 4"/>
        <p class="error" id="error-diners"></p>

        <label for="image">Recipe Image</label>
        <input type="file" id="image" name="image" accept="image/*" />
        <p class="error" id="error-image"></p>

        <label for="description">Description</label>
        <textarea
          id="description"
          name="description"
          rows="3"
          placeholder="Brief description..."
        ></textarea>
        <p class="error" id="error-description"></p>

        <label for="ingredients">Ingredients</label>
        <textarea
          id="ingredients"
          name="ingredients"
          rows="5"
          placeholder="e.g. 200g pasta, 2 eggs, 100g pancetta..."
        ></textarea>
        <p class="error" id="error-ingredients"></p>

        <div class="steps-group">
          <label>Instructions</label>
          <div id="steps-container">
            <div class="step-field">
              <input type="text" name="instructions[]" placeholder="Step 1..." />
            </div>
          </div>
          <button type="button" id="add-step-btn" class="btn-secondary">+ Add Step</button>
        </div>
        <p class="error" id="error-instructions"></p>


        <div class="accessing-error" id=adding-error></div>

        <div class="form-actions">
          <button type="submit" class="btn-primary">Submit Recipe</button>
          <a href="../dashboard/dashboard.php" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </main>
    <?php include '../components/footer.php'; ?>
    <script src="../js/add-recipe-jquery.js" type="application/javascript"></script>
  </body>
</html>
