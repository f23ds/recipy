//Mostrar comments de una receta
function loadComments() {
  fetch("../recipe/loadComments.php")
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

  fetch("../recipe/check_author_of_recipy.php")
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

            fetch("../recipe/addComment.php", {
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