const loginForm = document.querySelector("#login-form form");
const emailInput = document.querySelector('input[name="email"]');
const passwordInput = document.querySelector('input[name="password"]');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = emailInput.value;
    const password = passwordInput.value;

    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    fetch("../actions/authentication/login_action.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(loginData => {
        if (loginData.status === true) {
            window.location.href = loginData.page + '.php';
        } else {
            if (loginData.email) {
                emailInput.value = loginData.email;
            }
            const errorElement = document.querySelector(".errorMessage");
            errorElement.innerHTML = loginData.message;
            errorElement.style.display = "block";
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});