const loginForm = document.getElementById('loginForm');
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');

if (togglePassword && password) {
    togglePassword.addEventListener('click', () => {
        const showing = password.type === 'text';
        password.type = showing ? 'password' : 'text';
        togglePassword.textContent = showing ? 'Show' : 'Hide';
    });
}

if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
        const email = document.getElementById('email');
        if (!email.value.trim() || !password.value.trim()) {
            e.preventDefault();
            alert('Please enter both email and password.');
        }
    });
}
