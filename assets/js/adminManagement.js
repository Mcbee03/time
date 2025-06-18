$(document).ready(function() {
    // Password toggle functionality
    $(document).on('click', '.password-toggle', function() {
        const input = $(this).closest('.input-group').find('input');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Add admin form validation and submission
    $('#addAdminForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const password = form.find('input[name="password"]').val();
        const confirmPassword = form.find('input[name="confirm_password"]').val();
        
        if (password.length < 8) {
            showAlert('danger', 'Password must be at least 8 characters long');
            return;
        }
        
        if (password !== confirmPassword) {
            showAlert('danger', 'Passwords do not match');
            return;
        }
        
        const submitBtn = form.find('#addAdminBtn');
        submitBtn.prop('disabled', true);
        submitBtn.find('.spinner-border').removeClass('d-none');
        
        $.ajax({
            url: '../logic/AdminManagement/addAdminLogic.php',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#addModal').modal('hide');
                    form.trigger('reset');
                    showAlert('success', 'Admin added successfully');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', response.message || 'Error adding admin');
                }
            },
            error: function(xhr, status, error) {
                showAlert('danger', 'Error adding admin: ' + error);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.find('.spinner-border').addClass('d-none');
            }
        });
    });

    // Edit admin modal data loading
    $(document).on('click', '.edit-btn', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        
        const editBtn = $(this);
        editBtn.prop('disabled', true);
        editBtn.html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '../logic/AdminManagement/getAdminData.php',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(admin) {
                $('#edit_id').val(admin.Id);
                $('#edit_pb_number').val(admin.PBNum);
                $('#edit_member_id').val(admin.MemberID);
                $('#edit_name').val(admin.Name);
                $('#edit_username').val(admin.username);
            },
            error: function(xhr, status, error) {
                showAlert('danger', 'Error fetching admin data: ' + error);
            },
            complete: function() {
                editBtn.prop('disabled', false);
                editBtn.html('<i class="fas fa-edit"></i>');
            }
        });
    });

    // Edit admin form submission
    $('#editAdminForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('#editAdminBtn');
        submitBtn.prop('disabled', true);
        submitBtn.find('.spinner-border').removeClass('d-none');
        
        $.ajax({
            url: '../logic/AdminManagement/editAdminLogic.php',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    showAlert('success', 'Admin updated successfully');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', response.message || 'Error updating admin');
                }
            },
            error: function(xhr, status, error) {
                showAlert('danger', 'Error updating admin: ' + error);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.find('.spinner-border').addClass('d-none');
            }
        });
    });

    // Delete button click handler
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const adminId = $(this).data('id');
        const adminName = $(this).data('name');
        
        $('#deleteModal').find('.modal-body').html(`Are you sure you want to delete admin <strong>${adminName}</strong>?`);
        $('#delete_id').val(adminId);
        $('#deleteModal').modal('show');
    });

    // Delete confirmation handler
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm" role="status"></span> Deleting...');
        
        $.ajax({
            url: '../logic/AdminManagement/deleteAdmin.php',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    showAlert('success', 'Admin deleted successfully');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', response.message || 'Error deleting admin');
                }
            },
            error: function(xhr, status, error) {
                showAlert('danger', 'Error deleting admin: ' + error);
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.html('Yes, Delete');
            }
        });
    });

    // Alert function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        $('.alert-container').html(alertHtml);
        
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
});