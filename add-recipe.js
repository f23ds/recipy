// Previsualización de imagen
function addRecipe() {

    const errorDiv = document.getElementById("adding-error");
    errorDiv.style.display = "none";

    document.getElementById("add_recipe").addEventListener("submit", function (e) {

        e.preventDefault();

        const errorDiv = document.getElementById("adding-error");
        errorDiv.textContent = "";
        errorDiv.style.display = "none";

        const title = document.myForm.title.value.trim();
        const diners = document.myForm.diners.value.trim();
        const ingredients = document.myForm.ingredients.value.trim();
        const instructions = document.myForm.instructions.value.trim();

        const image = document.getElementById("image");

        let no_adding = true;

        if (!title) {
            document.getElementById("error-title").textContent = "❌ Please enter the title of the recipe.";
            no_adding = false;
        } else {
            document.getElementById("error-title").textContent = ""
        }

        if (!diners) {
            document.getElementById("error-diners").textContent = "❌ Please enter the amount of diners.";
            no_adding = false;
        } else {
            document.getElementById("error-diners").textContent = ""
        }

        if (!description) {
            document.getElementById("error-description").textContent = "❌ Please enter a brief description.";
            no_adding = false;
        } else {
            document.getElementById("error-description").textContent = ""
        }

        if (!ingredients) {
            document.getElementById("error-ingredients").textContent = "❌ Please enter the ingredients.";
            no_adding = false;
        } else {
            document.getElementById("error-ingredients").textContent = ""
        }

        if (!instructions) {
            document.getElementById("error-instructions").textContent = "❌ Please enter the instructions.";
            no_adding = false;
        } else {
            document.getElementById("error-instructions").textContent = ""
        }

        if (image.files.length === 0) {
            document.getElementById("error-image").textContent = "❌ Please enter an image.";
            no_adding = false;
        } else {
            document.getElementById("error-image").textContent = ""
        }

        if (!no_adding) return;

        const form = document.getElementById("add_recipe");
        const formData = new FormData(form);

        fetch("add-recipe.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "dashboard.php";
                } else {
                    document.getElementById("adding-error").textContent = data.error;
                    errorDiv.style.display = "block";
                }
            });

    });
}

document.addEventListener('DOMContentLoaded', addRecipe);