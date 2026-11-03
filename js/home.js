
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("menuToggle");
    const nav = document.getElementById("navLinks");

    if (!toggle || !nav) {
        return;
    }

    function closeMenu() {
        nav.classList.remove("show");
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-label", "Open navigation");
        toggle.textContent = "☰";
    }

    toggle.addEventListener("click", function () {
        const isOpen = nav.classList.toggle("show");

        toggle.setAttribute("aria-expanded", String(isOpen));
        toggle.setAttribute(
            "aria-label",
            isOpen ? "Close navigation" : "Open navigation"
        );
        toggle.textContent = isOpen ? "✕" : "☰";
    });

    nav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            closeMenu();
        });
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeMenu();
            toggle.focus();
        }
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 700) {
            closeMenu();
        }
    });
});
