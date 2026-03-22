const sidebar = document.getElementById('sidebar');
const toggle = document.getElementById('sidebarToggle');
const flashToast = document.getElementById('flashToast');
const loader = document.getElementById('globalLoader');

if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
        if (window.innerWidth <= 780) {
            sidebar.classList.toggle('open');
        } else {
            sidebar.classList.toggle('collapsed');
        }
    });
}

if (flashToast) {
    setTimeout(() => {
        flashToast.style.opacity = '0';
        flashToast.style.transform = 'translateY(-8px)';
        setTimeout(() => flashToast.remove(), 250);
    }, 3500);
}

document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
        if (loader) {
            loader.style.display = 'grid';
        }
    });
});
