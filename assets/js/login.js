$(document).ready(function () {
    // Password toggle
    $('.toggle-password').click(function () {
        const input = $('#password');
        const icon = $(this);

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    let hasInteracted = false;

    // Real-time validation
    $('input[name="username"], #password').on('input', function () {
        hasInteracted = true;
        validateForm();
    });

    function validateForm() {
        const usernameField = $('input[name="username"]');
        const passwordField = $('#password');
        const username = usernameField.val().trim();
        const password = passwordField.val().trim();
        const submitBtn = $('button[name="signin"]');
        let isValid = true;

        if (hasInteracted) {
            // Username validation
            if (username.length === 0) {
                usernameField.removeClass('is-valid').addClass('is-invalid');
                isValid = false;
            } else {
                usernameField.removeClass('is-invalid').addClass('is-valid');
            }

            // Password validation
            if (password.length < 8) {
                passwordField.removeClass('is-valid').addClass('is-invalid');
                isValid = false;
            } else {
                passwordField.removeClass('is-invalid').addClass('is-valid');
            }
        }

        submitBtn.prop('disabled', !isValid);
    }

    // Handle form submission - UPDATED URL
    $('#loginForm').submit(function (e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = $('button[name="signin"]');
        const submitText = submitBtn.find('.submit-text');
        const spinner = submitBtn.find('.spinner-border');

        submitText.text('Authenticating...');
        spinner.removeClass('d-none');
        submitBtn.prop('disabled', true);
        $('.error-text').addClass('d-none').text('');
        $('input').removeClass('is-invalid is-valid');

        $.ajax({
            url: '/logic/Login/loginLogic.php', // Updated path
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    // Redirect to stored URL or default page
                    window.location.href = res.redirect;
                } else {
                    $('.error-text')
                        .removeClass('d-none')
                        .hide()
                        .slideDown()
                        .text(res.message);
                    $('#password').val('').focus();
                }
            },  
            error: function () {
                $('.error-text')
                    .removeClass('d-none')
                    .hide()
                    .slideDown()
                    .text('An unexpected error occurred. Please try again.');
            },
            complete: function () {
                submitText.text('Sign In');
                spinner.addClass('d-none');
                validateForm();
            }
        });
    });
    // No initial validation
    // validateForm(); ← removed this line so it doesn't turn red on load
});
