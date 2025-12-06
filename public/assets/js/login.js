document.addEventListener("DOMContentLoaded", function() {
    const passwordField = document.getElementById("password");
    const toggleButton = document.querySelector(".fg-toggle-password");
    const toggleIcon = document.getElementById("toggleIcon");

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
});

