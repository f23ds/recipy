function validateForm() {
  const errorDiv = document.getElementById("accessing-error");
  errorDiv.style.display = "none";

  document.getElementById("register_form").addEventListener("submit", function (e) {

    e.preventDefault();
    const errorDiv = document.getElementById("accessing-error");
    errorDiv.textContent = "";
    errorDiv.style.display = "none";

    const name = document.myForm.name.value.trim();
    const username = document.myForm.username.value.trim();
    const email = document.myForm.email.value.trim();
    const password = document.myForm.password.value.trim();
    const confirm_pw = document.myForm.confirm_pw.value.trim();

    let no_registration = true;

    if (!name) {
      document.getElementById("error-name").textContent = "❌ Please enter your name.";
      no_registration = false;
    } else {
      document.getElementById("error-name").textContent = ""
    }

    if (!username) {
      document.getElementById("error-surname").textContent = "❌ Please enter a username.";
      no_registration = false;
    } else {
      document.getElementById("error-surname").textContent = ""
    }

    if (!email) {
      document.getElementById("error-email").textContent = "❌ Please enter your email.";
      no_registration = false;
    } else {
      document.getElementById("error-email").textContent = ""
    }

    if (!password) {
      document.getElementById("error-password").textContent = "❌ Please enter a password.";
      no_registration = false;
    } else {
      document.getElementById("error-password").textContent = ""
    }

    if (!confirm_pw) {
      document.getElementById("error-confirm_pw").textContent = "❌ Please confirm your password.";
      no_registration = false;
    } else if (password !== confirm_pw) {
      document.getElementById("error-confirm_pw").textContent = "❌ Both passwords must be the same.";
      no_registration = false;
    } else {
      document.getElementById("error-confirm_pw").textContent = "";
    }


    if (!no_registration) return;

    fetch("registration.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        email: email,
        password: password,
        name: name,
        username: username,
        confirm_pw: confirm_pw
      })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.href = data.redirect;
        } else if (data.username) {
          document.getElementById("accessing-error").textContent = data.username;
          errorDiv.style.display = "block";
        } else if (data.email) {
          document.getElementById("accessing-error").textContent = data.email;
          errorDiv.style.display = "block";
        } else {
          document.getElementById("accessing-error").textContent = data.error;
          errorDiv.style.display = "block";
        }
      });

    return true;
  });
}

document.addEventListener('DOMContentLoaded', validateForm);