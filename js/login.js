const loginForm = document.getElementById('loginForm');
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');
const loginError = document.getElementById('loginError');
const signInBtn = document.getElementById('signInBtn');

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
        const emailVal = email.value.trim();
        const passVal = password.value.trim();
        const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);

        if (loginError) {
            loginError.classList.add('hidden');
            loginError.textContent = '';
        }

        if (!emailVal || !passVal) {
            e.preventDefault();
            if (loginError) {
                loginError.textContent = 'Please enter both email and password.';
                loginError.classList.remove('hidden');
            }
            return;
        }

        if (!emailValid) {
            e.preventDefault();
            if (loginError) {
                loginError.textContent = 'Please provide a valid email address.';
                loginError.classList.remove('hidden');
            }
            return;
        }

        if (passVal.length < 6) {
            e.preventDefault();
            if (loginError) {
                loginError.textContent = 'Password must be at least 6 characters.';
                loginError.classList.remove('hidden');
            }
            return;
        }

        if (signInBtn) {
            signInBtn.classList.add('loading');
            signInBtn.setAttribute('aria-busy', 'true');
            signInBtn.setAttribute('disabled', 'disabled');
        }
    });
}
