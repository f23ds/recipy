document.addEventListener("DOMContentLoaded", () => {
  const likeBtn = document.querySelector(".btn-like");
  const icon = likeBtn.querySelector("i");

  likeBtn.addEventListener("click", () => {
    likeBtn.classList.toggle("liked");
    if (likeBtn.classList.contains("liked")) {
      icon.classList.remove("fa-regular");
      icon.classList.add("fa-solid");
      fetch("saveRecipe.php?code=1")
        .then((res) => res.json())
        .then((success) => {
          if (success) {
            console.log("Acción completada");
          } else {
            console.error("Error en la consulta");
          }
        });
    } else {
      icon.classList.remove("fa-solid");
      icon.classList.add("fa-regular");
      fetch("saveRecipe.php?code=0")
        .then((res) => res.json())
        .then((success) => {
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

  fetch("check_recipe_saved.php")
    .then((res) => res.json())
    .then((success) => {
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
  document.querySelectorAll(".like-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const icon = btn.querySelector("i");

      if (icon.classList.contains("fa-regular")) {
        icon.classList.remove("fa-regular");
        icon.classList.add("fa-solid");
        btn.classList.add("liked");
        fetch("saveRecipe.php?code=1")
          .then((res) => res.json())
          .then((success) => {
            if (success) {
              console.log("Acción completada");
            } else {
              console.error("Error en la consulta");
            }
          });
      } else {
        icon.classList.remove("fa-solid");
        icon.classList.add("fa-regular");
        btn.classList.remove("liked");
        fetch("saveRecipe.php?code=0")
          .then((res) => res.json())
          .then((success) => {
            if (success) {
              console.log("Acción completada");
            } else {
              console.error("Error en la consulta");
            }
          });
      }
    });
  });
});

function saveRecipe(id) {
  fetch("exploringRecipe.php?exploring_recipe_id=" + id)
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

function loadComments() {
  fetch("loadComments.php")
    .then((res) => res.json())
    .then((data) => {
      showComments(data);
    })
    .catch((err) => {
      console.error("Error while loading comments:", err);
    });
}

function showComments(comments) {
  const contenedor = document.getElementById("comments");
  contenedor.innerHTML = "";

  if (comments.length > 0) {
    comments.forEach((comment) => {
      const div = document.createElement("div");
      div.id = `comment-${comment.id}`;
      div.classList.add("comment");
      div.innerHTML = `
              <strong>@${comment.author}:</strong> ${comment.content}
            `;
      contenedor.appendChild(div);
    });
  } else {
    contenedor.innerHTML += `<p class=no-recipes>There are no comments on this recipe.</p>`;
  }

  fetch("check_author_of_recipy.php")
    .then((res) => res.json())
    .then((success) => {
      if (!success) {
        const form = document.createElement("form");
        form.className = "comment-form";
        form.setAttribute("name", "comment_form");
        form.setAttribute("id", "comment_form");
        form.setAttribute("onsubmit", "checkComment()");

        const input = document.createElement("input");
        input.type = "text";
        input.name = "comment";
        input.placeholder = "Write a comment...";

        const button = document.createElement("button");
        button.type = "submit";
        button.textContent = "Post";

        form.appendChild(input);
        form.appendChild(button);

        contenedor.appendChild(form);

        document
          .getElementById("comment_form")
          .addEventListener("submit", function (e) {
            e.preventDefault();

            const comment = document.comment_form.comment.value.trim();

            if (!comment) {
              return;
            }

            fetch("addComment.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                comment: comment,
              }),
            })
              .then((res) => res.json())
              .then((data) => {
                if (data.success) {
                  document.getElementById("comment_form").reset();
                  loadComments();
                } else {
                  alert(data.error || "Error when inserting");
                }
              })
              .catch((err) => {
                console.error("Error:", err);
                alert("Network error when inserting the comment");
              });

            return true;
          });
      }
    });
}

document.addEventListener("DOMContentLoaded", loadComments);

function renderRecipes(recipes) {
  const container = document.querySelector(".carousel-track");
  container.innerHTML = ""; // Limpia el contenido anterior

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

    const img = document.createElement("img");
    img.src = recipe.image;
    img.alt = "Recipe";
    card.appendChild(img);

    const info = document.createElement("div");
    info.className = "recipe-info";

    const title = document.createElement("a");
    title.className = "recipe-title";
    title.href = `recipe.php?recipe_id=${recipe.id}`;
    title.textContent = recipe.title;

    const user = document.createElement("span");
    user.className = "recipe-user";
    user.innerHTML = `<a href="#">@${recipe.author}</a>`;

    const likeBtn = document.createElement("button");
    likeBtn.className = "like-btn" + (recipe.saved ? " liked" : "");
    likeBtn.setAttribute("onclick", `saveRecipe(${recipe.id})`);
    likeBtn.innerHTML = `<i class="${
      recipe.saved ? "fa-solid" : "fa-regular"
    } fa-heart"></i>`;

    info.appendChild(title);
    info.appendChild(user);
    info.appendChild(likeBtn);
    card.appendChild(info);

    container.appendChild(card);
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
  const searchInput = document.getElementById("search-input");
  const searchIcon = document.getElementById("search-icon"); // nuevo

  function performSearch() {
    const title = searchInput.value.trim();
    if (title !== "") {
      fetch(`search-recipe.php?title=${encodeURIComponent(title)}`)
        .then((res) => res.json())
        .then((data) => {
          renderRecipes(data);
        })
        .catch((err) => {
          console.error("Error fetching recipes:", err);
        });
    }
  }

  searchIcon.addEventListener("click", performSearch);

  searchInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      performSearch();
    }
  });
});
