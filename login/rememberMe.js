function alertRmb() {
    const emailInput = document.getElementsByName("email")[0];
    const passwordInput = document.getElementsByName("password")[0]
    // la variabile remember contiene un valore booleano
    // ( dipendente dallo stato della checkbox )
    if (!emailInput.value) {
        document.getElementById("error-email").textContent = "❌ Please enter your email.";
        return false;
    } else {
        document.getElementById("error-email").textContent = ""
    }

    if (!passwordInput.value) {
        document.getElementById("error-password").textContent = "❌ Please enter a password.";
        return false;
    } else {
        document.getElementById("error-password").textContent = ""
    }

    var remember = document.getElementById("remember").checked;
    if (remember) {
        localStorage.setItem("email", emailInput.value);
        localStorage.setItem("password", passwordInput.value);
        localStorage.setItem("remember", "true");
    }
    else {
        localStorage.setItem("remember", "false");
    }
}

// rememberMe.js

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