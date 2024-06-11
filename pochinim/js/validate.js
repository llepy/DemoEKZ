document.getElementById('registerForm').addEventListener('submit', function (e) {
    var password = document.getElementById('password').value;
    var username = document.getElementById('username').value;

    if (username.length < 3) {
        alert('Логин должен быть минимум 3 символа');
        e.preventDefault();
    }

    if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
        alert('Пароль должен содержать хотя бы одну букву в верхнем и нижнем регистре');
        e.preventDefault();
    }
});
