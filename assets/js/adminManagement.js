$(document).ready(function () {
    //  Password Toggle
    $(document).on('click', '.password-toggle', function () {
        const input = $(this).closest('.input-group').find('input[type="password"], input[type="text"]');
        const icon = $(this).find('i');
        if (input.length && icon.length) {
            const isPassword = input.attr('type') === 'password';
            input.attr('type', isPassword ? 'text' : 'password');
            icon.toggleClass('fa-eye fa-eye-slash');
        }
    });

    //  Add Admin
    $(document).on('submit', '#addAdminForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    form[0].reset();
                    $('#addModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(response.message || 'Failed to add admin.');
                    submitBtn.prop('disabled', false);
                }
            },
            error: function () {
                toastr.error('Server error while adding admin.');
                submitBtn.prop('disabled', false);
            }
        });
    });

    //  Load Admin Data into Edit Modal
    $(document).on('click', '.edit-btn', function (e) {
        e.preventDefault();
        const id = $(this).data('id');

        if (!id) {
            toastr.error('Admin ID missing.');
            return;
        }

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

    //  Submit Edit Admin
    $(document).on('submit', '#editAdminForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(response.message || 'Update failed.');
                    submitBtn.prop('disabled', false);
                }
            },
            error: function () {
                toastr.error('Server error while updating admin.');
                submitBtn.prop('disabled', false);
            }
        });
    });

    //  Delete - Load Info into Modal
    $(document).on('click', '.delete-btn', function () {
        const adminId = $(this).data('id');
        const adminName = $(this).data('name');

        if (!adminId) {
            toastr.error('No Admin ID provided.');
            return;
        }

        $('#delete_id').val(adminId);
        $('#adminToDelete').text(adminName || 'this admin');
    });

    //  Submit Delete Admin
    $(document).on('submit', '#deleteForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const spinner = submitBtn.find('.spinner-border');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'Admin deleted.');
                    $('#deleteModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 800);
                } else {
                    toastr.error(response.message || 'Failed to delete admin.');
                }
            },
            error: function () {
                toastr.error('Server error while deleting admin.');
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });
});
