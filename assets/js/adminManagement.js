$(document).ready(function() {
    // Password toggle
    $(document).on('click', '.password-toggle', function() {
        const input = $(this).closest('.input-group').find('input');
        const icon = $(this).find('i');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        icon.toggleClass('fa-eye fa-eye-slash');
    });

    // Add Admin Form
    $(document).on('submit', '#addAdminForm', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        // Disable button immediately
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    form.trigger('reset');
                    $('#addModal').modal('hide');
                    
                    // Force button enable + reload after modal closes
                    setTimeout(() => {
                        submitBtn.prop('disabled', false);
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                toastr.error('Server error');
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Edit Admin Load Data
    $(document).on('click', '.edit-btn', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        
        $.ajax({
            url: '../logic/AdminManagement/getAdminData.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(admin) {
                $('#edit_id').val(admin.Id);
                $('#edit_pb_number').val(admin.PBNum);
                $('#edit_member_id').val(admin.MemberID);
                $('#edit_name').val(admin.Name);
                $('#edit_username').val(admin.username);
            },
            error: function() {
                toastr.error('Failed to load admin data');
            }
        });
    });

    // Delete Confirmation
    $(document).on('click', '.delete-btn', function() {
        const adminId = $(this).data('id');
        const adminName = $(this).data('name');
        $('#delete_id').val(adminId);
        $('#deleteModal .modal-body').html(`Delete admin <strong>${adminName}</strong>?`);
    });
});