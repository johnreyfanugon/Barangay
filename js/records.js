document.querySelectorAll('input[name="temp"]').forEach((tempInput) => {
    tempInput.addEventListener('input', () => {
        const value = Number(tempInput.value);
        if (value > 45 || value < 30) {
            tempInput.setCustomValidity('Temperature should be between 30 and 45 C.');
        } else {
            tempInput.setCustomValidity('');
        }
    });
});
