$(document).ready(function () {
    // 🔐 Password Toggle
    $(document).on('click', '.password-toggle', function () {
        const input = $(this).closest('.input-group').find('input[type="password"], input[type="text"]');
        const icon = $(this).find('i');
        if (input.length && icon.length) {
            const isPassword = input.attr('type') === 'password';
            input.attr('type', isPassword ? 'text' : 'password');
            icon.toggleClass('fa-eye fa-eye-slash');
        }
    });

    // ➕ ADD Admin - Submit
     // Add Admin Form
    $(document).on('submit', '#addAdminForm', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        // Disable button immediately
        submitBtn.prop('disabled', true).html('Adding...');
        
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

    // ✏️ EDIT Admin - Load to modal
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        if (!id) return toastr.error('Admin ID missing');

        $.ajax({
            url: '../logic/AdminManagement/getAdminData.php',
            type: 'POST',
            data: { id },
            dataType: 'json',
            success: function (admin) {
                if (admin && admin.Id) {
                    $('#edit_id').val(admin.Id);
                    $('#edit_pb_number').val(admin.PBNum);
                    $('#edit_member_id').val(admin.MemberID);
                    $('#edit_name').val(admin.Name);
                    $('#edit_username').val(admin.username);
                    $('#editModal').modal('show');
                } else {
                    toastr.error('Invalid admin data received.');
                }
            },
            error: function () {
                toastr.error('Failed to fetch admin data.');
            }
        });
    });

    // ✏️ EDIT Admin - Submit
    $(document).on('submit', '#editAdminForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('Updating...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editModal').one('hidden.bs.modal', function () {
                        location.reload();
                    }).modal('hide');
                    toastr.success('Admin updated successfully');
                } else {
                    toastr.error(response.message || 'Update failed.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function () {
                toastr.error('Server error while updating admin.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // 🗑️ DELETE Admin - Fill modal
    $(document).on('click', '.delete-btn', function () {
        const adminId = $(this).data('id');
        const adminName = $(this).data('name');
        $('#delete_id').val(adminId);
        $('#adminToDelete').text(adminName || 'this admin');
        $('#deleteModal').modal('show');
    });

    // 🗑️ DELETE Admin - Submit
    $(document).on('submit', '#deleteForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('Deleting...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#deleteModal').one('hidden.bs.modal', function () {
                        location.reload();
                    }).modal('hide');
                    toastr.success(response.message || 'Admin deleted successfully');
                } else {
                    toastr.error(response.message || 'Failed to delete admin.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function () {
                toastr.error('Server error while deleting admin.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // 🔁 Reset forms and buttons after modal close
    $('#addModal, #editModal, #deleteModal').on('hidden.bs.modal', function () {
        const form = $(this).find('form')[0];
        const submitBtn = $(this).find('button[type="submit"]');
        if (form) form.reset();
        if (submitBtn.length) {
            const btnId = submitBtn.attr('id');
            const label = btnId === 'confirmDeleteBtn' ? 'Delete' : (btnId === 'editAdminBtn' ? 'Update' : 'Add');
            submitBtn.prop('disabled', false).html(label);
        }

        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
});
