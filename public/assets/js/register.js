document.addEventListener("DOMContentLoaded", function() {
    // Function to toggle password visibility for a given input and icon
    function setupPasswordToggle(passwordFieldId, toggleIconId) {
        const passwordField = document.getElementById(passwordFieldId);
        const toggleButton = passwordField.nextElementSibling;
        const toggleIcon = document.getElementById(toggleIconId);

        if (toggleButton) {
            toggleButton.addEventListener("click", function() {
                const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                passwordField.setAttribute("type", type);
                
                if (type === "password") {
                    toggleIcon.classList.remove("fa-eye");
                    toggleIcon.classList.add("fa-eye-slash");
                } else {
                    toggleIcon.classList.remove("fa-eye-slash");
                    toggleIcon.classList.add("fa-eye");
                }
            });
        }
    }

    // Set up toggles for both password fields
    setupPasswordToggle("password", "toggleIcon1");
    setupPasswordToggle("confirm_password", "toggleIcon2");
});


