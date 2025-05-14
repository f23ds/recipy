function loadRecipes() {
    fetch("load_recipes.php")
        .then(res => res.json())
        .then(data => {
            showRecipes(data);
        })
        .catch(err => {
            console.error('Error al cargar recetas:', err);
        });
}

function showRecipes(recetas) {
    const contenedor = document.getElementById('resultado');
    contenedor.innerHTML = '';

    if (recetas.length == 0) {
        const p = document.createElement('p');
        p.classList.add("no-recipes");
        p.innerHTML = "You haven't added any recipes yet.";
        contenedor.appendChild(p);
        return;
    }

    recetas.forEach(receta => {
        const div = document.createElement('div');
        div.classList.add("recipe-card");
        div.id = `receta-${receta.id}`;
        div.innerHTML = `<a href=recipe.php?recipe_id=${receta.id}>${receta.title}</a>`;
        div.addEventListener("click", function () {
            mostrarDetalleReceta(receta);
        });
        contenedor.appendChild(div);
    });
}

function loadSavedRecipes() {
    fetch("load_saved_recipes.php")
        .then(res => res.json())
        .then(data => {
            showSavedRecipes(data);
        })
        .catch(err => {
            console.error('Error al cargar recetas:', err);
        });
}

function showSavedRecipes(recetas) {
    const contenedor = document.getElementById('resultado2');
    contenedor.innerHTML = '';

    if (recetas.length == 0) {
        const p = document.createElement('p');
        p.classList.add("no-recipes");
        p.innerHTML = "You haven't saved any recipes yet.";
        contenedor.appendChild(p);
        return;
    }

    recetas.forEach(receta => {
        const div = document.createElement('div');
        div.classList.add("recipe-card");
        div.id = `receta-${receta.id}`;
        div.innerHTML = `<a href=recipe.php?recipe_id=${receta.id}>${receta.title}</a>`;
        div.addEventListener("click", function () {
            mostrarDetalleReceta(receta);
        });
        contenedor.appendChild(div);
    });
}

document.addEventListener('DOMContentLoaded', loadRecipes);

document.addEventListener('DOMContentLoaded', loadSavedRecipes);

document.addEventListener("DOMContentLoaded", () => {
    const likeBtn = document.querySelector(".btn-like");
    const icon = likeBtn.querySelector("i");

    likeBtn.addEventListener("click", () => {
        likeBtn.classList.toggle("liked");
        if (likeBtn.classList.contains("liked")) {
            icon.classList.remove("fa-regular");
            icon.classList.add("fa-solid");
            fetch("saveRecipe.php?code=1").then(res => res.json())
                .then(success => {
                    if (success) {
                        console.log("Acción completada");
                    } else {
                        console.error("Error en la consulta");
                    }
                });
        } else {
            icon.classList.remove("fa-solid");
            icon.classList.add("fa-regular");
            fetch("saveRecipe.php?code=0").then(res => res.json())
                .then(success => {
                    if (success) {
                        console.log("Acción completada");
                    } else {
                        console.error("Error en la consulta");
                    }
                });
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const likeBtn = document.querySelector(".btn-like");
    const icon = likeBtn.querySelector("i");

    fetch("check_recipe_saved.php").then(res => res.json())
        .then(success => {
            likeBtn.classList.toggle("liked");
            if (success) {
                icon.classList.remove("fa-regular");
                icon.classList.add("fa-solid");
            } else {
                icon.classList.remove("fa-solid");
                icon.classList.add("fa-regular");
            }
        });
});

document.addEventListener("DOMContentLoaded", () => {

    const logoutLink = document.querySelector('.nav-links a[href="#logout"]');
    const modal = document.getElementById('logout-modal');
    const cancelBtn = document.getElementById('cancel-logout');

    if (logoutLink && modal && cancelBtn) {
        logoutLink.addEventListener('click', (e) => {
            e.preventDefault();
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
});

