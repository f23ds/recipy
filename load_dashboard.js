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

function saveRecipe(id) {
    fetch("exploringRecipy.php?exploring_recipe_id=" + id).then(res => res.json())
        .then(success => {
            if (success) {
                return true;
            } else {
                return false;
            }
        });
}

window.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.carousel-wrapper').forEach(wrapper => {
      const track = wrapper.querySelector('.carousel-track');
      const btnLeft = wrapper.querySelector('.left');
      const btnRight = wrapper.querySelector('.right');

      btnLeft.addEventListener('click', () => {
        track.scrollLeft -= 300;
      });

      btnRight.addEventListener('click', () => {
        track.scrollLeft += 300;
      });
    });
  });

