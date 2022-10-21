alert(goooo)
const passField = document.getElementById('password');
const toggleButton = document.querySelector(".field i");

toggleButton.addEventListener('click', () => {
    if (passField.type === "text") {
    passField.type='password';
    toggleButton.classList.remove('bi-eye-fill')
    toggleButton.classList.add('bi-eye-slash-fill')
    } else {
        passField.type = 'text';
        toggleButton.classList.add('bi-eye-fill')
        toggleButton.classList.remove('bi-eye-slash-fill')
    }
})