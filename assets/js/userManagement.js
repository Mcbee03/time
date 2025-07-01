$(document).ready(function () {

    // ➕ ADD User
    $(document).on('submit', '#addUserForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('Adding...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#addMemberModal').modal('hide');
                    toastr.success('User added successfully');
                    setTimeout(() => {
                        submitBtn.prop('disabled', false).html(originalText);
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(res.message || 'Failed to add user.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function () {
                toastr.error('Server error occurred.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // ✏️ LOAD User to Edit Modal
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        if (!id) return toastr.error('User ID missing.');

        $.ajax({
            url: '../logic/UserManagement/getUserData.php',
            type: 'POST',
            data: { id },
            dataType: 'json',
            success: function (res) {
                if (res.success && res.data) {
                    const user = res.data;
                    $('#edit_id').val(user.Id);
                    $('#edit_pb_number').val(user.PBNum);
                    $('#edit_member_id').val(user.MemberID);
                    $('#edit_name').val(user.Name);
                    $('#edit_committee_id').val(user.Committee_ID);
                    $('#editMemberModal').modal('show');
                } else {
                    toastr.error(res.message || 'Invalid user data received.');
                }
            },
            error: function () {
                toastr.error('AJAX error while fetching user.');
            }
        });
    });

    // ✏️ SUBMIT Edit User
    $(document).on('submit', '#editUserForm', function (e) {
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
            success: function (res) {
                if (res.success) {
                    $('#editMemberModal').modal('hide');
                    toastr.success('User updated successfully');
                    setTimeout(() => {
                        submitBtn.prop('disabled', false).html(originalText);
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(res.message || 'Failed to update user.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function () {
                toastr.error('Server error while updating.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // 🗑️ OPEN Delete Modal
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('#delete_id').val(id);
        $('#userToDelete').text(name || 'this user');
        $('#deleteMemberModal').modal('show');
    });

    // 🗑️ SUBMIT Delete
    $(document).on('submit', '#deleteUserForm', function (e) {
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
            success: function (res) {
                if (res.success) {
                    $('#deleteMemberModal').modal('hide');
                    toastr.success('User deleted successfully');
                    setTimeout(() => {
                        submitBtn.prop('disabled', false).html(originalText);
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(res.message || 'Failed to delete user.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function () {
                toastr.error('Server error while deleting user.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // 🔁 Reset forms and buttons when modal closes
    $('#addMemberModal, #editMemberModal, #deleteMemberModal').on('hidden.bs.modal', function () {
        const form = $(this).find('form')[0];
        if (form) form.reset();

        const submitBtn = $(this).find('button[type="submit"]');
        if (submitBtn.length) {
            const btnId = submitBtn.attr('id');
            let label = 'Submit';
            if (btnId === 'editUserBtn') label = 'Update';
            if (btnId === 'confirmDeleteUserBtn') label = 'Delete';
            if (btnId === 'addUserBtn') label = 'Add';
            submitBtn.prop('disabled', false).html(label);
        }
    });

    // 🔍 Real-time Search
    $(document).on('keyup', '#searchInput', function () {
        const filter = $(this).val().toLowerCase().trim();
        $('#userTable tbody tr').each(function () {
            const name = $(this).find('td:eq(1)').text().toLowerCase();
            const memberId = $(this).find('td:eq(2)').text().toLowerCase();
            const pbNum = $(this).find('td:eq(3)').text().toLowerCase();

            $(this).toggle(
                name.includes(filter) ||
                memberId.includes(filter) ||
                pbNum.includes(filter)
            );
        });
    });

});
