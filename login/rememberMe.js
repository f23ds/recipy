function checkLogin() {
    const errorDiv = document.getElementById("accessing-error");
    errorDiv.style.display = "none";

    document.getElementById("login_form").addEventListener("submit", function (e) {

        e.preventDefault();
        const errorDiv = document.getElementById("accessing-error");
        errorDiv.textContent = "";
        errorDiv.style.display = "none";

        const emailInput = document.getElementsByName("email")[0];
        const passwordInput = document.getElementsByName("password")[0]

        let no_login = true;

        if (!emailInput.value) {
            document.getElementById("error-email").textContent = "❌ Please enter your email.";
            no_login = false;
        } else {
            document.getElementById("error-email").textContent = ""
        }

        if (!passwordInput.value) {
            document.getElementById("error-password").textContent = "❌ Please enter a password.";
            no_login = false;
        } else {
            document.getElementById("error-password").textContent = ""
        }

        if (!no_login) return;

        fetch("login.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                email: emailInput.value,
                password: passwordInput.value
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    var remember = document.getElementById("remember").checked;
                    if (remember) {
                        localStorage.setItem("email", emailInput.value);
                        localStorage.setItem("password", passwordInput.value);
                        localStorage.setItem("remember", "true");
                    }
                    else {
                        localStorage.setItem("remember", "false");
                    }
                    window.location.href = data.redirect;
                } else if (data.email) {
                    document.getElementById("accessing-error").textContent = data.email;
                    errorDiv.style.display = "block";
                } else if (data.password) {
                    document.getElementById("accessing-error").textContent = data.password;
                    errorDiv.style.display = "block";
                } else {
                    document.getElementById("accessing-error").textContent = data.error;
                    errorDiv.style.display = "block";
                }
            });
    });
}

document.addEventListener('DOMContentLoaded', checkLogin);

window.onload = function () {
    const emailInput = document.getElementsByName("email")[0];
    const passwordInput = document.getElementsByName("password")[0];
    const rememberCheckbox = document.getElementById("remember");

    // Cargar los datos almacenados si existen
    if (localStorage.getItem("remember") === "true") {
        emailInput.value = localStorage.getItem("email") || "";
        passwordInput.value = localStorage.getItem("password") || "";
        rememberCheckbox.checked = true;
    }
};