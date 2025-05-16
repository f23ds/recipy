// Previsualización de imagen
function changeProfile() {
    const profileInput = document.getElementById("profileImage");
    const preview = document.getElementById("profilePreview");

    const saveBtn = document.getElementById("saveBtn");
    saveBtn.style.display = "none";

    profileInput.addEventListener("change", () => {
        const file = profileInput.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
        }
    });

    // Activar edición de campos
    const editBtn = document.getElementById("editBtn");
    editBtn.addEventListener("click", () => {
        document.querySelectorAll(".edit-form input").forEach((el) => {
            el.removeAttribute("readonly");
        });
        const saveBtn = document.getElementById("saveBtn");
        saveBtn.style.display = "block";
    });

    document.getElementById("edit_profile").addEventListener("submit", function (e) {

        e.preventDefault();

        const usernameInput = document.getElementById("username");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        fetch("edit-profile-db.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                username: usernameInput.value,
                email: emailInput.value,
                password: passwordInput.value
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("username").value = data.username;
                    document.getElementById("email").value = data.email;
                    const saveBtn = document.getElementById("saveBtn");
                    saveBtn.style.display = "none";
                    document.querySelectorAll(".edit-form input").forEach((el) => {
                        el.setAttribute("readonly", true);
                    });
                } else if (data.username) {
                    document.getElementById("error-username").textContent = data.email;
                } else if (data.email) {
                    document.getElementById("error-email").textContent = data.password;
                } else {
                    document.getElementById("error").textContent = data.error;
                }
            });

    });
}

document.addEventListener('DOMContentLoaded', changeProfile);