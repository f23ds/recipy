function validateForm() {
    if (document.myForm.name.value == "") {
        alert("Insert your name");
        return false;
    }
    if (!document.myForm.name.value.includes(" ")) {
        alert("Insert your surname");
        return false;
    }
    if (document.myForm.email.value == "") {
        alert("Insert your email");
        return false;
    }
    if (document.myForm.password.value == "") {
        alert("Insert a password");
        return false;
    }
    if (document.myForm.confirm_pw.value == "") {
        alert("Confirm your password");
        return false;
    }
    if (document.myForm.password.value != document.myForm.confirm_pw.value) {
        alert("Both passwords must be the same");
        return false;
    }
    alert("Data inserted correctly");
    return true;
}