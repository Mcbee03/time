$(document).ready(function() {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        
        // Toggle eye icon
        $(this).toggleClass('fa-eye-slash fa-eye');
    });
    
    // Form submission (you can add your AJAX/login logic here)
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        // Add your login logic here
    });
});