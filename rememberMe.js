function alertRmb() {
    const emailInput = document.getElementsByName("email")[0];
    const passwordInput = document.getElementsByName("password")[0]
    // la variabile remember contiene un valore booleano
    // ( dipendente dallo stato della checkbox )
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