
document.addEventListener("DOMContentLoaded", function () {

    // =================================
    // DARK / LIGHT MODE
    // =================================

    const themeToggleBtn = document.getElementById("theme-toggle");
    const htmlElement = document.documentElement;

    // Load saved theme
    let savedTheme = localStorage.getItem("sportshub_theme") || "dark";

    htmlElement.setAttribute("data-theme", savedTheme);

    function updateThemeIcon(theme) {
        if (!themeToggleBtn) return;

        if (theme === "dark") {
            themeToggleBtn.innerHTML = '<i class="fa-solid fa-sun"></i>';
        } else {
            themeToggleBtn.innerHTML = '<i class="fa-solid fa-moon"></i>';
        }
    }

    updateThemeIcon(savedTheme);

    // Theme button click
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function () {

            const currentTheme =
                htmlElement.getAttribute("data-theme");

            const newTheme =
                currentTheme === "dark" ? "light" : "dark";

            htmlElement.setAttribute("data-theme", newTheme);

            localStorage.setItem("sportshub_theme", newTheme);

            updateThemeIcon(newTheme);

        });
    }

    // =================================
    // MOBILE MENU
    // =================================

    const menuToggle = document.getElementById("mobile-menu");
    const navLinks = document.querySelector(".nav-links");

    if (menuToggle && navLinks) {

        menuToggle.addEventListener("click", function () {
            navLinks.classList.toggle("show");
        });

    }

});
