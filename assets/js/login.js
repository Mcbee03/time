$(document).ready(function() {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);

        // Toggle eye icon
        $(this).toggleClass('fa-eye-slash fa-eye');
    });

    // Static login: Only allow specific username and password
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        const username = $('#username').val().trim();
        const password = $('#password').val();

        // Static credentials: admin / admin123
        if (username === 'admin' && password === 'password123') {
            alert('Login successful!');
            window.location.href = 'adminManagement.php';
        } else if (username === '') {
            alert('Please enter your username.');
        } else if (password === '') {
            alert('Please enter your password.');
        } else {
            alert('Invalid username or password. Please try again.');
        }
    });
});
