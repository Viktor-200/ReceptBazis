const toggleBtn = document.getElementById("toggleBtn");
const themeIcon = document.getElementById("themeIcon");
const logo = document.getElementById("logo");

let isDarkMode = localStorage.getItem("darkMode") === "true";

function applyTheme() {
    if (isDarkMode) {
        document.body.classList.add("dark-mode");
        document.body.classList.remove("light-mode");
        themeIcon.classList.remove("fa-moon");
        themeIcon.classList.add("fa-sun");
        logo.src = "img/feketelogo.png";
    } else {
        document.body.classList.add("light-mode");
        document.body.classList.remove("dark-mode");
        themeIcon.classList.remove("fa-sun");
        themeIcon.classList.add("fa-moon");
        logo.src = "img/feherlogo.png";
    }
}

applyTheme();

toggleBtn.addEventListener("click", () => {
    isDarkMode = !isDarkMode;
    localStorage.setItem("darkMode", isDarkMode);
    applyTheme();
});