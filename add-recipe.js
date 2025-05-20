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
        const description = document.myForm.description.value.trim();
        const ingredients = document.myForm.ingredients.value.trim();

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

        const steps = Array.from(document.querySelectorAll('input[name="instructions[]"]'))
                     .map(input => input.value.trim());

        if (steps.length === 0 || steps.some(step => step === "")) {
          document.getElementById('error-instructions').textContent = "❌ All steps must be filled.";
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

document.addEventListener("DOMContentLoaded", () => {
    const stepsContainer = document.getElementById("steps-container");
    const addStepBtn = document.getElementById("add-step-btn");

    let stepCount = 1;

    function updateRemoveButtons() {
        const allSteps = stepsContainer.querySelectorAll('.step-field');
        allSteps.forEach((step, index) => {
            const removeBtn = step.querySelector('.remove-step-btn');
            if (removeBtn) {
                removeBtn.remove(); // elimina todos los botones
            }
            // solo el último paso puede tener el botón de eliminar
            if (index === allSteps.length - 1 && allSteps.length > 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.classList.add('remove-step-btn');
                btn.textContent = 'Remove';

                btn.addEventListener('click', () => {
                    step.remove();
                    stepCount--;
                    updateRemoveButtons(); // vuelve a dejar solo uno con botón
                });

                step.appendChild(btn);
            }
        });
    }

    addStepBtn.addEventListener("click", () => {
        stepCount++;

        const newStep = document.createElement("div");
        newStep.classList.add("step-field");

        newStep.innerHTML = `
        <input type="text" name="instructions[]" placeholder="Step ${stepCount}..." />
      `;

        stepsContainer.appendChild(newStep);
        updateRemoveButtons();
    });
});

