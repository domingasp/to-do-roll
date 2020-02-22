// Sets main list area to fill remaining space to allow scrolling easily
document.getElementById("mainBodyDiv").style.height = (document.documentElement.clientHeight - document.getElementById("headerBar").offsetHeight).toString() + "px";

// Adds window event listener for resize
window.addEventListener("resize", function(e) {
    document.getElementById("mainBodyDiv").style.height = (document.documentElement.clientHeight - document.getElementById("headerBar").offsetHeight).toString() + "px";
})

// Toggle password input fields to either display text or obscured password
function togglePasswordView() {
    var passwordField = document.getElementById("password");
    
    if (passwordField.type == "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}

// Validates the fields for register and sign in forms
function validateForm() {
    var nameValid = true, emailValid = true, passwordValid = true;
    
    if (nameField = document.getElementById("name")) {
        nameValid = validateName(nameField);
        nameField.addEventListener("input", function() {
            validateName(nameField);
        });
    }

    if (emailField = document.getElementById("email")) {
        emailValid = validateEmail(emailField);
        emailField.addEventListener("input", function() {
            validateEmail(emailField);
        });
    }

    if (passwordField = document.getElementById("password")) {
        passwordValid = validatePassword(passwordField);
        passwordField.addEventListener("input", function() {
            validatePassword(passwordField);
        });
    }

    return nameValid && emailValid && passwordValid;
}

// Validates the name field
function validateName(elem) {
    input = elem.value;
    if (input.length == 0) {
        // Only create an error message if one does not exist already
        if (!elem.nextElementSibling.classList.contains("input-error")) {
            createErrorMessage(elem, " Please enter a name.");
            // Add class to input field to have a red border
            elem.classList.add("invalid-input");
        } else {
            elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " Please enter a name.";
        }
        return false;
    } else if(input.length > 255) {
        // Only create an error message if one does not exist already
        if (!elem.nextElementSibling.classList.contains("input-error")) {
            createErrorMessage(elem, " The name is too long.");
            // Add class to input field to have a red border
            elem.classList.add("invalid-input");
        } else {
            elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " The name is too long.";
        }
        return false;
    } else {
        // Only remove an error message if one exists already
        if (elem.nextElementSibling.classList.contains("input-error")) {
            removeErrorMessage(elem);
            // Remove the class from the input field
            elem.classList.remove("invalid-input");
        }
        return true;
    }
}

// Validates the email field
function validateEmail(elem) {
    input = elem.value;
    if (input.length == 0) {
        // Only create an error message if one does not exist already
        if (!elem.nextElementSibling.classList.contains("input-error")) {
            createErrorMessage(elem, " Please enter an email.");
            // Add class to input field to have a red border
            elem.classList.add("invalid-input");
        } else {
            elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " Please enter an email.";
        }
        return false;
    } else if(input.length > 255) {
        // Only create an error message if one does not exist already
        if (!elem.nextElementSibling.classList.contains("input-error")) {
            createErrorMessage(elem, " The email is too long.");
            // Add class to input field to have a red border
            elem.classList.add("invalid-input");
        } else {
            elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " The email is too long.";
        }
        return false;
    } else {
        // Regex to test emails against
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(String(input).toLowerCase())) {
            // Only create an error message if one does not exist already
            if (!elem.nextElementSibling.classList.contains("input-error")) {
                createErrorMessage(elem, " Email format is incorrect.");
                // Add class to input field to have a red border
                elem.classList.add("invalid-input");
            } else {
                elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " Email format is incorrect.";
            }
            return false;
        } else {
            // Only remove an error message if one exists already
            if (elem.nextElementSibling.classList.contains("input-error")) {
                removeErrorMessage(elem);
                // Remove the class from the input field
                elem.classList.remove("invalid-input");
            }
            return true;
        }
    }
}

// Validates the password field
function validatePassword(elem) {
    input = elem.value;
    if (input.length == 0) {
        // Only create an error message if one does not exist already
        if (!elem.nextElementSibling.classList.contains("input-error")) {
            createErrorMessage(elem, " Please enter a password.");
            // Add class to input field to have a red border
            elem.classList.add("invalid-input");
        } else {
            elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " Please enter a password.";
        }
        return false;
    } else if (input.length < 8) {
        // Only create an error message if one does not exist already
        if (!elem.nextElementSibling.classList.contains("input-error")) {
            createErrorMessage(elem, " Password should be 8 characters or longer.");
            // Add class to input field to have a red border
            elem.classList.add("invalid-input");
        } else {
            elem.nextElementSibling.innerHTML = "<i class='fas fa-exclamation-circle'></i>" + " Password should be 8 characters or longer.";
        }
        return false;
    } else {
        // Only remove an error message if one exists already
        if (elem.nextElementSibling.classList.contains("input-error")) {
            removeErrorMessage(elem);
            // Remove the class from the input field
            elem.classList.remove("invalid-input");
        }
        return true;
    }
}

// Creates and adds an error message to the relevant position in the page
function createErrorMessage(elem, error) {
    errorMessage = document.createElement("P");
    errorMessage.classList.add("input-error");

    errorMessage.innerHTML += "<i class='fas fa-exclamation-circle'></i>" + error;

    elem.parentNode.insertBefore(errorMessage, elem.nextElementSibling);
}

// Removes error message from the relevant position
function removeErrorMessage(elem) {
    elem.nextElementSibling.remove();
}

function closeBanner(elem) {
    banner = elem.parentNode;
    banner.style.animation = "bannerUp 0.5s forwards";
    setTimeout(function() {
        banner.remove();
    },500);
}

// Adds a new form item in the list
function newListItem(elem) {
    
}

// Prevents a new line being created when the Return key is pressed
function preventNewLine(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
}