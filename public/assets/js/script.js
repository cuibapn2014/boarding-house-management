$(document).ready(function () {
    $("#loading").fadeOut();

    $(document).on("click", ".logo", () => (window.location.href = "/"));

    $(window).on("scroll", function (e) {
        const scrollTop = $(window).scrollTop();

        if (scrollTop > 300) {
            $("#scroll-to-top").fadeIn();
        } else {
            $("#scroll-to-top").fadeOut();
        }
    });

    $(window).trigger("scroll");

    $(document).on("click", "#scroll-to-top", function (e) {
        e.preventDefault();

        $(window).scrollTop(0);
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (event) {
        const dropdown = document.querySelector(".nav-item.dropdown");
        const dropdownMenu = document.getElementById("userDropdownMenu");

        if (dropdown && dropdownMenu && !dropdown.contains(event.target)) {
            dropdownMenu.classList.remove("show");
            const dropdownToggle = document.getElementById("userDropdown");
            if (dropdownToggle) {
                dropdownToggle.setAttribute("aria-expanded", "false");
            }
        }
    });

    // Close dropdown when clicking on dropdown items (except logout button)
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownItems = document.querySelectorAll(
            "#userDropdownMenu .dropdown-item:not(button)"
        );
        dropdownItems.forEach(function (item) {
            item.addEventListener("click", function () {
                const dropdownMenu =
                    document.getElementById("userDropdownMenu");
                if (dropdownMenu) {
                    dropdownMenu.classList.remove("show");
                }
            });
        });
    });

    // Close dropdown on ESC key
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            const dropdownMenu = document.getElementById("userDropdownMenu");
            const dropdownToggle = document.getElementById("userDropdown");

            if (dropdownMenu && dropdownMenu.classList.contains("show")) {
                dropdownMenu.classList.remove("show");
                if (dropdownToggle) {
                    dropdownToggle.setAttribute("aria-expanded", "false");
                    dropdownToggle.focus();
                }
            }
        }
    });
});

// User Dropdown Toggle Function
function toggleUserDropdown(event) {
    event.preventDefault();
    event.stopPropagation();

    const dropdownMenu = document.getElementById("userDropdownMenu");
    const dropdownToggle = document.getElementById("userDropdown");

    if (dropdownMenu) {
        // Toggle show class
        const isShown = dropdownMenu.classList.contains("show");

        // Close all other dropdowns first
        document
            .querySelectorAll(".dropdown-menu.show")
            .forEach(function (menu) {
                menu.classList.remove("show");
            });

        // Toggle current dropdown
        if (!isShown) {
            dropdownMenu.classList.add("show");
            dropdownToggle.setAttribute("aria-expanded", "true");
        } else {
            dropdownMenu.classList.remove("show");
            dropdownToggle.setAttribute("aria-expanded", "false");
        }
    }
}
