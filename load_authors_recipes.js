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

    if (recetas.length==0) {
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
        div.innerHTML = `${receta.title}`;
        contenedor.appendChild(div);
    });
}

document.addEventListener('DOMContentLoaded', loadRecipes);

