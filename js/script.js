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

// Adds a new form list
function newList(elem) {
    if (!document.getElementById("newListForm")) {
        newListForm = document.createElement("FORM");
        newListForm.classList.add("list-div");
        newListForm.id = "newListForm"

        newListForm.innerHTML = "<input class=\"list-title list-input\" type=\"text\" name=\"newListInput\" id=\"newListInput\" placeholder=\"List name...\">";
        newListForm.innerHTML += "<div class=\"new-list-control-div\"><button class=\"new-list-btn cancel-btn\" type=\"button\" onclick=\"removeNewListForm(this)\">Cancel</button><button class=\"new-list-btn create-btn\" type=\"submit\">Create</button></div>";
        
        $(newListForm).insertBefore(elem.parentNode);
        
        $("#newListInput").focus();

        // Variable to hold request
        var request;
        // Bind to the submit event of our form
        $("#newListForm").submit(function(event){
            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();

            // Remove undo button if it exists
            if ($("#undoBtnId")) {
                $("#undoBtnId").remove();
            }

            inputValue = $('#newListForm').find('input[name="newListInput"]').val();
            inputError = inputValue.length == 0 ? "Please enter a list title." : (inputValue > 255 ? "The title is too long." : "");
            // Client side validation first, prevents unnecessary requests
            if (inputError.length == 0) {
                // Abort any pending request
                if (request) {
                    request.abort();
                }
                // Setup some local variables
                var $form = $(this);

                // Let's select and cache all the fields
                var $inputs = $form.find("input, select, button, textarea");

                // Serialize the data in the form
                var serializedData = $form.serialize();

                // Let's disable the inputs for the duration of the Ajax request.
                // Note: we disable elements AFTER the form data has been serialized.
                // Disabled form elements will not be serialized.
                $inputs.prop("disabled", true);

                // Fire off the request to /form.php
                request = $.ajax({
                    url: "add_list.php",
                    type: "post",
                    dataType: "json",
                    data: serializedData
                });

                // Callback handler that will be called on success
                request.done(function (response, textStatus, jqXHR){
                    // If error is returned
                    if (response["error"]) {
                        if ($("#newListInput").next().hasClass("new-list-error-p")) {
                            $("#newListInput").next().html(response["error"]);
                        } else {
                            p = document.createElement("P");
                            p.innerHTML = response["error"];
                            p.classList.add("new-list-error-p");

                            $(p).insertAfter("#newListInput");
                        }
                    // If successfully added to the database
                    } else {
                        if ($("#newListInput").next().hasClass("new-list-error-p")) {
                            $("#newListInput").next().remove();
                        }

                        nextElement = $("#newListForm").next();

                        // Remove the form to replace with the new list
                        $("#newListForm").remove();

                        // Add the new list
                        listDiv = document.createElement("DIV");
                        $(listDiv).attr("data-id", response["success"][1]);
                        $(listDiv).addClass("list-div");
                        $(listDiv).html("<button class=\"list-title\" onclick=\"openListModal(this)\">" + response["success"][0] + "</button><div class=\"add-item-div\"><button onclick=\"newListItem(this)\" class=\"add-item-btn\"><i class=\"fas fa-plus icon-space\"></i>New Item</button></div>");
                        $(listDiv).insertBefore(nextElement);
                    }
                });

                // Callback handler that will be called on failure
                request.fail(function (jqXHR, textStatus, errorThrown){
                    console.error(
                        "The following error occurred: "+
                        textStatus, errorThrown
                    );
                });

                // Callback handler that will be called regardless
                request.always(function () {
                    // Reenable the inputs
                    $inputs.prop("disabled", false);
                });
            } else {
                if ($("#newListInput").next().hasClass("new-list-error-p")) {
                    $("#newListInput").next().html(inputError);
                } else {
                    p = document.createElement("P");
                    p.innerHTML = inputError;
                    p.classList.add("new-list-error-p");

                    $(p).insertAfter("#newListInput");
                }
            }
        });
    } else {
        console.log("Creating one already");
    }
}

// Removes new form list
function removeNewListForm(elem) {
    $("#newListForm").remove();
}

// Adds a new form item in the list
function newListItem(elem) {
    if (!document.getElementById("newItemForm")) {
        newItemForm = document.createElement("FORM");
        newItemForm.classList.add("item-div");
        newItemForm.id = "newItemForm"

        newItemForm.innerHTML = "<textarea name=\"newItemTextareaName\" id=\"newItemTextareaName\" class=\"item-a item-textarea\" placeholder=\"Item Title...\" onKeyDown=\"preventNewLine(event)\"></textarea>";
        newItemForm.innerHTML += "<div class=\"new-item-btn-div\"><button type=\"submit\" class=\"item-btn save-btn\">Save</button><button type=\"button\" class=\"item-btn cancel-btn\" onclick=\"removeNewListItem(this)\">Cancel</button></div>";

        elem.parentNode.parentNode.insertBefore(newItemForm, elem.parentNode);

        $("#newItemTextareaName").focus();

        // Variable to hold request
        var request;
        // Bind to the submit event of our form
        $("#newItemForm").submit(function(event){
            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();

            // Remove undo button if it exists
            if ($("#undoBtnId")) {
                $("#undoBtnId").remove();
            }

            inputValue = $("#newItemForm").find("textarea[name=\"newItemTextareaName\"]").val();
            inputError = inputValue.length == 0 ? "Please enter an item title." : (inputValue.length > 255 ? "The title is too long." : "");

            if (inputError.length == 0) {
                // Abort any pending request
                if (request) {
                    request.abort();
                }
                // Setup some local variables
                var $form = $(this);

                // Let's select and cache all the fields
                var $inputs = $form.find("input, select, button, textarea");

                // Serialize the data in the form
                var serializedData = $form.serialize() + "&listId=" + $(this.parentNode).attr("data-id");

                // Let's disable the inputs for the duration of the Ajax request.
                // Note: we disable elements AFTER the form data has been serialized.
                // Disabled form elements will not be serialized.
                $inputs.prop("disabled", true);

                // Fire off the request to /form.php
                request = $.ajax({
                    url: "add_item.php",
                    type: "post",
                    dataType: "json",
                    data: serializedData
                });

                // Callback handler that will be called on success
                request.done(function (response, textStatus, jqXHR){
                    // If error is returned
                    if (response["error"]) {
                        if ($("#newItemForm").next().hasClass("new-item-error-p")) {
                            $("#newItemForm").next().html(response["error"]);
                        } else {
                            p = document.createElement("P");
                            p.innerHTML = response["error"];
                            p.classList.add("new-item-error-p");

                            $(p).insertAfter("#newItemForm");
                        }
                    // If successfully added to the database
                    } else {
                        if ($("#newItemForm").next().hasClass("new-item-error-p")) {
                            $("#newItemForm").next().remove();
                        }

                        nextElement = $("#newItemForm").next();

                        $("#newItemForm").remove();

                        // Add the new item
                        itemDiv = document.createElement("DIV");
                        $(itemDiv).addClass("item-div");
                        $(itemDiv).html("<button class=\"item-a\">" + response["success"] + "</button><button class=\"tick-a check-a\"><i class=\"fas fa-check\"></i></button>");
                        $(itemDiv).insertBefore(nextElement);
                    }
                });

                // Callback handler that will be called on failure
                request.fail(function (jqXHR, textStatus, errorThrown){
                    console.error(
                        "The following error occurred: "+
                        textStatus, errorThrown
                    );
                });

                // Callback handler that will be called regardless
                request.always(function () {
                    // Reenable the inputs
                    $inputs.prop("disabled", false);
                });
            } else {
                if ($("#newItemForm").next().hasClass("new-item-error-p")) {
                    $("#newItemForm").next().html(inputError);
                } else {
                    p = document.createElement("P");
                    p.innerHTML = inputError;
                    p.classList.add("new-item-error-p");

                    $(p).insertAfter("#newItemForm");
                }
            }
        });

    } else {
        console.log("Creating one already");
    }
}

// Removes new form item
function removeNewListItem(elem) {
    if ($("#newItemForm").next().hasClass("new-item-error-p")) {
        $("#newItemForm").next().remove();
    }
    $("#newItemForm").remove();
}

// Prevents a new line being created when the Return key is pressed
function preventNewLine(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
}

// Creates a list modal
function openListModal(elem) {
    // If modal exists then do no create a new one
    if (!$("#modalId").length) {
        // Background modal
        modalDiv = document.createElement("DIV");
        modalDiv.id = "modalId";
        $(modalDiv).addClass("modal");
        
        // Modal box
        modalContent = document.createElement("DIV");
        $(modalContent).addClass("list-modal-content");

        $(modalDiv).click(function(event) {
            if (event.target == modalDiv) {
                modalDiv.remove();
                blur();
                elem.focus();
            }
        });

        // -------------------------- MODAL HEADER -------------------------- //
        // Modal header div
        modalHeader = document.createElement("DIV");
        $(modalHeader).addClass("list-modal-header");

        // Header span
        modalHeaderSpan = document.createElement("SPAN");
        $(modalHeaderSpan).addClass("list-modal-header-span");
        $(modalHeaderSpan).html("<i class=\"fas fa-edit icon-space\"></i>Edit List");

        modalHeaderBtn = document.createElement("BUTTON");
        $(modalHeaderBtn).addClass("list-modal-close");
        $(modalHeaderBtn).html("<i class=\"fas fa-times\"></i>");

        $(modalHeaderBtn).click(function(event) {
            if ($("#modalId").length) {
                $("#modalId").remove();
                blur();
                elem.focus();
            }
        });

        // -------------------------- MODAL FORM -------------------------- //
        modalForm = document.createElement("FORM");
        modalForm.id = "listModalForm";
        $(modalForm).addClass("list-modal-form");

        modalLabel = document.createElement("LABEL");
        $(modalLabel).addClass("form-label");
        $(modalLabel).attr("for", "listModalInput");
        $(modalLabel).html("List Name");

        modalInput = document.createElement("INPUT");
        modalInput.id = "listModalInput";
        $(modalInput).addClass("form-input");
        $(modalInput).attr("type", "text");
        $(modalInput).attr("name", "listModalInput");
        $(modalInput).attr("value", elem.innerHTML);
        $(modalInput).attr("data-id", $(elem.parentNode).attr("data-id"));

        // -------------------------- MODAL CONTROLS -------------------------- //
        modalControls = document.createElement("DIV");
        $(modalControls).addClass("list-modal-controls");

        modalSaveCancelDiv = document.createElement("DIV");
        $(modalSaveCancelDiv).addClass("list-modal-save-cancel");

        modalSaveBtn = document.createElement("BUTTON");
        $(modalSaveBtn).addClass("list-modal-btn list-modal-save-btn create-btn");
        $(modalSaveBtn).attr("type", "submit");
        $(modalSaveBtn).html("Save");

        modalCancelBtn = document.createElement("BUTTON");
        $(modalCancelBtn).addClass("list-modal-btn list-modal-cancel-btn");
        $(modalCancelBtn).attr("type", "button");
        $(modalCancelBtn).html("Cancel");

        $(modalCancelBtn).click(function(event) {
            if ($("#modalId").length) {
                $("#modalId").remove();
                blur();
                elem.focus();
            }
        });

        modalDeleteBtn = document.createElement("BUTTON");
        $(modalDeleteBtn).addClass("list-modal-btn cancel-btn");
        $(modalDeleteBtn).attr("type", "button");
        $(modalDeleteBtn).html("<i class=\"fas fa-trash icon-space\"></i> Delete List");

        // -------------------------- MODAL CONSTRUCTION -------------------------- //
        $(modalDiv).append(modalContent);
        $(modalContent).append(modalHeader);
        $(modalHeader).append(modalHeaderSpan);
        $(modalHeader).append(modalHeaderBtn);
        $(modalContent).append(modalForm);
        $(modalForm).append(modalLabel);
        $(modalForm).append(modalInput);
        $(modalForm).append(modalControls);
        $(modalControls).append(modalSaveCancelDiv);
        $(modalSaveCancelDiv).append(modalSaveBtn);
        $(modalSaveCancelDiv).append(modalCancelBtn);
        $(modalControls).append(modalDeleteBtn);

        $(modalDiv).insertAfter("#mainBodyDiv");
        $(modalHeaderBtn).focus();

        // Variable to hold request
        var request;
        // Bind to the submit event of our form
        $(modalForm).submit(function(event){
            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();

            // Remove undo button if it exists
            if ($("#undoBtnId")) {
                $("#undoBtnId").remove();
            }

            inputValue = $(modalForm).find("input[name=\"listModalInput\"]").val();
            inputError = inputValue.length == 0 ? "Please enter a list name." : (inputValue.length > 255 ? "The name is too long." : "");

            if (inputError.length == 0) {
                // Check if the input and current values are the same
                if (inputValue.trim() != elem.innerHTML) {
                    // Abort any pending request
                    if (request) {
                        request.abort();
                    }
                    // Setup some local variables
                    var $form = $(this);

                    // Let's select and cache all the fields
                    var $inputs = $form.find("input, select, button, textarea");

                    // Serialize the data in the form
                    var serializedData = $form.serialize() + "&listId=" + $(modalInput).attr("data-id");

                    // Let's disable the inputs for the duration of the Ajax request.
                    // Note: we disable elements AFTER the form data has been serialized.
                    // Disabled form elements will not be serialized.
                    $inputs.prop("disabled", true);

                    // Fire off the request to /form.php
                    request = $.ajax({
                        url: "save_list_name.php",
                        type: "post",
                        dataType: "json",
                        data: serializedData
                    });

                    // Callback handler that will be called on success
                    request.done(function (response, textStatus, jqXHR){
                        // If error is returned
                        if (response["error"]) {
                            if ($("#listModalInput").next().hasClass("new-item-error-p")) {
                                $("#listModalInput").next().html(response["error"]);
                            } else {
                                p = document.createElement("P");
                                p.innerHTML = response["error"];
                                p.classList.add("new-item-error-p");

                                $(p).insertAfter("#listModalInput");
                            }
                        // If successfully added to the database
                        } else {
                            if ($("#listModalInput").next().hasClass("new-item-error-p")) {
                                $("#listModalInput").next().remove();
                            }

                            $(modalDiv).remove();

                            // Update list name
                            if (response["success"] != "No changes made") {
                                $(elem).html(response["success"]);
                            }
                        }
                    });

                    // Callback handler that will be called on failure
                    request.fail(function (jqXHR, textStatus, errorThrown){
                        console.error(
                            "The following error occurred: "+
                            textStatus, errorThrown
                        );
                    });

                    // Callback handler that will be called regardless
                    request.always(function () {
                        // Reenable the inputs
                        $inputs.prop("disabled", false);
                    });
                } else {
                    $(modalDiv).remove();
                }
            } else {
                if ($("#listModalInput").next().hasClass("new-item-error-p")) {
                    $("#listModalInput").next().html(inputError);
                } else {
                    p = document.createElement("P");
                    p.innerHTML = inputError;
                    p.classList.add("new-item-error-p");

                    $(p).insertAfter("#listModalInput");
                }
            }
        });

        $(modalDeleteBtn).click(function() {
            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();

            // Remove undo button if it exists
            if ($("#undoBtnId")) {
                $("#undoBtnId").remove();
            }

            // Abort any pending request
            if (request) {
                request.abort();
            }
            // Setup some local variables
            var $form = $(this);

            // Let's select and cache all the fields
            var $inputs = $form.find("input, select, button, textarea");

            // Serialize the data in the form
            var serializedData = "listId=" + $(modalInput).attr("data-id");

            // Let's disable the inputs for the duration of the Ajax request.
            // Note: we disable elements AFTER the form data has been serialized.
            // Disabled form elements will not be serialized.
            $inputs.prop("disabled", true);

            // Fire off the request to /form.php
            request = $.ajax({
                url: "delete_list.php",
                type: "post",
                dataType: "json",
                data: serializedData
            });

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                // If error is returned
                if (response["error"]) {
                    if ($("#listModalInput").next().hasClass("new-item-error-p")) {
                        $("#listModalInput").next().html(response["error"]);
                    } else {
                        p = document.createElement("P");
                        p.innerHTML = response["error"];
                        p.classList.add("new-item-error-p");

                        $(p).insertAfter("#listModalInput");
                    }
                // If successfully remove from database
                } else {
                    if ($("#listModalInput").next().hasClass("new-item-error-p")) {
                        $("#listModalInput").next().remove();
                    }

                    $(modalDiv).remove();
                    $(elem.parentNode).remove();

                    // Create an undo button
                    undoBtn = document.createElement("BUTTON");
                    $(undoBtn).addClass("undo-btn");
                    undoBtn.id = "undoBtnId";
                    $(undoBtn).html("<i class=\"fas fa-undo icon-space\"></i>Undo");
                    $(undoBtn).insertAfter("#mainBodyDiv");
                    undoBtn.style.animation = "buttonFromBotttom 0.2s forwards";
                    $(undoBtn).click(function() {
                        // Prevent default posting of form - put here to work in case of errors
                        event.preventDefault();

                        // Abort any pending request
                        if (request) {
                            request.abort();
                        }

                        // Serialize the data in the form
                        var serializedData = "listId=" + $(modalInput).attr("data-id");

                        // Fire off the request to /form.php
                        request = $.ajax({
                            url: "undo_delete_list.php",
                            type: "post",
                            dataType: "json",
                            data: serializedData
                        });

                        // Callback handler that will be called on success
                        request.done(function (response, textStatus, jqXHR){
                            // If error is returned
                            if (response["error"]) {
                                console.log(response["error"]);
                            // If re-add the list
                            } else {
                                location.reload();
                            }
                        });

                        // Callback handler that will be called on failure
                        request.fail(function (jqXHR, textStatus, errorThrown){
                            console.error(
                                "The following error occurred: "+
                                textStatus, errorThrown
                            );
                        });

                        // Callback handler that will be called regardless
                        request.always(function () {
                            // Reenable the inputs
                            $inputs.prop("disabled", false);
                        });
                    });
                }
            });

            // Callback handler that will be called on failure
            request.fail(function (jqXHR, textStatus, errorThrown){
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });

            // Callback handler that will be called regardless
            request.always(function () {
                // Reenable the inputs
                $inputs.prop("disabled", false);
            });
        });
    }
}

function closeModal() {

}