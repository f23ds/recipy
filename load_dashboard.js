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
    document.querySelectorAll(".like-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const icon = btn.querySelector("i");

            if (icon.classList.contains("fa-regular")) {
                icon.classList.remove("fa-regular");
                icon.classList.add("fa-solid");
                btn.classList.add("liked");
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
                btn.classList.remove("liked");
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
    fetch("exploringRecipe.php?exploring_recipe_id=" + id).then(res => res.json())
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

function loadComments() {
    fetch("loadComments.php")
        .then(res => res.json())
        .then(data => {
            showComments(data);
        })
        .catch(err => {
            console.error('Error while loading comments:', err);
        });
}

function showComments(comments) {
    const contenedor = document.getElementById('comments');
    contenedor.innerHTML = '';

    if (comments.length > 0) {
        comments.forEach(comment => {
            const div = document.createElement('div');
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

    fetch("check_author_of_recipy.php").then(res => res.json())
        .then(success => {
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

                document.getElementById("comment_form").addEventListener("submit", function (e) {

                    e.preventDefault();

                    const comment = document.comment_form.comment.value.trim();

                    if (!comment) {
                        return;
                    }

                    fetch("addComment.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            comment: comment
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById("comment_form").reset();
                                loadComments();
                            } else {
                                alert(data.error || "Error when inserting");
                            }
                        })
                        .catch(err => {
                            console.error("Error:", err);
                            alert("Network error when inserting the comment");
                        });

                    return true;
                });
            } 
        });

}

document.addEventListener('DOMContentLoaded', loadComments);



