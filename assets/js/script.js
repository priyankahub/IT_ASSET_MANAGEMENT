// Common JavaScript for IT Equipment Life Cycle Management System

// Confirmation before critical actions
function confirmAction(message) {
    return confirm(message);
}

// Simple form validation
function validateForm() {
    let inputs = document.querySelectorAll("input[required]");
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === "") {
            alert("Please fill all required fields");
            return false;
        }
    }
    return true;
}

// Show alert messages (used for notifications)
function showAlert(msg) {
    alert(msg);
}
