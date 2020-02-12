// Toggle password input fields to either display text or obscured password
function togglePasswordView(elem) {
    var passwordField = elem.parentElement.previousElementSibling;
    
    if (passwordField.type == "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}