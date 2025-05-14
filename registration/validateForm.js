function validateForm() {
    const name = document.myForm.name.value.trim();
    const username = document.myForm.username.value.trim();
    const email = document.myForm.email.value.trim();
    const password = document.myForm.password.value.trim();
    const confirm_pw = document.myForm.confirm_pw.value.trim();

    if (!name) {
      document.getElementById("error-name").textContent = "❌ Please enter your name.";
      return false;
    } else {
      document.getElementById("error-name").textContent = ""
    }

    if (!username) {
      document.getElementById("error-surname").textContent = "❌ Please enter a username.";
      return false;
    } else {
      document.getElementById("error-surname").textContent = ""
    }

    if (!email) {
      document.getElementById("error-email").textContent = "❌ Please enter your email.";
      return false;
    } else {
      document.getElementById("error-email").textContent = ""
    }

    if (!password) {
      document.getElementById("error-password").textContent = "❌ Please enter a password.";
      return false;
    } else {
      document.getElementById("error-password").textContent = ""
    }

    if (!confirm_pw) {
      document.getElementById("error-confirm_pw").textContent = "❌ Please confirm your password.";
      return false;
    } else {
      document.getElementById("error-confirm_pw").textContent = ""
    }

    if (password && confirm_pw && password !== confirm_pw) {
      document.getElementById("error-confirm_pw").textContent = "❌ Both passwords must be the same.";
      return false;
    } else {
      document.getElementById("error-confirm_pw").textContent = ""
    }

    return true;
}