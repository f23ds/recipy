function saveRecipe(id) {
  fetch("../explore/exploringRecipe.php?exploring_recipe_id=" + id)
    .then((res) => res.json())
    .then((success) => {
      if (success) {
        return true;
      } else {
        return false;
      }
    });
}

window.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".carousel-wrapper").forEach((wrapper) => {
    const track = wrapper.querySelector(".carousel-track");
    const btnLeft = wrapper.querySelector(".left");
    const btnRight = wrapper.querySelector(".right");

    const updateArrowVisibility = () => {
      const scrollLeft = track.scrollLeft;
      const maxScrollLeft = track.scrollWidth - track.clientWidth;

      btnLeft.style.display = scrollLeft > 5 ? "block" : "none";
      btnRight.style.display =
        scrollLeft < maxScrollLeft - 5 ? "block" : "none";
    };

    btnLeft.addEventListener("click", () => {
      track.scrollBy({ left: -300, behavior: "smooth" });
    });

    btnRight.addEventListener("click", () => {
      track.scrollBy({ left: 300, behavior: "smooth" });
    });

    track.addEventListener("scroll", updateArrowVisibility);

    updateArrowVisibility();
  });
});

function renderRecipes(recipes) {
  const container = document.querySelector(".carousel-track");
  container.innerHTML = "";

  if (recipes.length === 0) {
    const msg = document.createElement("p");
    msg.className = "no-recipes";
    msg.textContent = "There are no recipes to explore.";
    container.appendChild(msg);
    return;
  }

  recipes.forEach((recipe) => {
    const card = document.createElement("div");
    card.className = "recipe-card";
    card.id = `recipe-${recipe.id}`;

    card.innerHTML = `
      <img src="${recipe.image}" alt="Recipe" />
      <div class="recipe-info">
        <a class="recipe-title" href="../recipe/recipe.php?recipe_id=${
          recipe.id
        }">${recipe.title}</a>
        <span class="recipe-user">
          <a href="../explore/userKitchen.php?user=${recipe.author}">@${
      recipe.author
    }</a>
          <span class="diners-count"> • ${
            recipe.diners
          } diners • <span class="likes-count">${
      recipe.times_saved
    }</span> likes</span>
        </span>
        <button class="like-btn${
          recipe.saved ? " liked" : ""
        }" onclick="saveRecipe(${recipe.id})" data-id="${recipe.id}">
          <i class="${recipe.saved ? "fa-solid" : "fa-regular"} fa-heart"></i>
        </button>
      </div>
    `;

    container.appendChild(card);

    const likeBtn = card.querySelector(".like-btn");
    const likesCount = card.querySelector(".likes-count");

    likeBtn.addEventListener("click", () => {
      const liked = likeBtn.classList.toggle("liked");
      const icon = likeBtn.querySelector("i");
      icon.className = liked ? "fa-solid fa-heart" : "fa-regular fa-heart";

      let currentLikes = parseInt(likesCount.textContent);
      currentLikes += liked ? 1 : -1;
      likesCount.textContent = currentLikes;

      const code = liked ? 1 : 0;
      fetch(`../recipe/saveRecipe.php?code=${code}&recipe_id=${recipe.id}`)
        .then((res) => res.json())
        .then((success) => {
          if (!success) {
            console.error("Error en la consulta");
          }
        });
    });
  });

  const track = document.querySelector(".carousel-track");
  const wrapper = document.querySelector(".carousel-wrapper");
  if (track && wrapper) {
    const btnLeft = wrapper.querySelector(".left");
    const btnRight = wrapper.querySelector(".right");

    const updateArrowVisibility = () => {
      const scrollLeft = track.scrollLeft;
      const maxScrollLeft = track.scrollWidth - track.clientWidth;

      btnLeft.style.display = scrollLeft > 5 ? "block" : "none";
      btnRight.style.display =
        scrollLeft < maxScrollLeft - 5 ? "block" : "none";
    };

    updateArrowVisibility();
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const searchBtn = document.getElementById("search-btn");
  const searchInput = document.getElementById("search-input");

  searchBtn.addEventListener("click", () => {
    if (searchInput.value != "") {
      fetch(`../explore/search-recipe.php?title=` + searchInput.value)
        .then((res) => res.json())
        .then((data) => {
          renderRecipes(data);
        })
        .catch((err) => {
          console.error("Error fetching recipes:", err);
        });
    }
  });

  searchInput.addEventListener("keyup", (e) => {
    if (e.key === "Enter" && searchInput.value != "") {
      searchBtn.click();
    }
  });
});
