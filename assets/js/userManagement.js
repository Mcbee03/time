$(document).ready(function () {
    // ➕ ADD User
    $(document).on('submit', '#addUserForm', function (e) {
        e.preventDefault();
        const form = $(this)[0];
        const formData = new FormData(form);

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#addMemberModal').modal('hide');
                    toastr.success('User added successfully');
                    setTimeout(() => location.reload(), 500);
                } else {
                    toastr.error(res.message || 'Failed to add user.');
                }
                submitBtn.prop('disabled', false).html(originalText);
            },
            error: function () {
                toastr.error('Server error occurred.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    // ✏️ EDIT User - Load data into modal
    // ✏️ EDIT User - Load data into modal
    $(document).on('click', '.editUserBtn', function() {
        const userId = $(this).data('id');
        console.log("Edit button clicked for user ID:", userId); // Debug
        
        if (!userId || userId <= 0) {
            toastr.error('Invalid user ID');
            return;
        }

        // Show loading state
        const editBtn = $(this);
        const originalBtnHtml = editBtn.html();
        editBtn.html('<span class="spinner-border spinner-border-sm"></span> Loading...').prop('disabled', true);

        $.ajax({
            url: '../logic/UserManagement/getUserData.php',
            type: 'POST',
            data: { id: userId },
            dataType: 'json',
            success: function(response) {
                console.log("Server response:", response); // Debug
                
                // Restore button state
                editBtn.html(originalBtnHtml).prop('disabled', false);

                if (response.success && response.data) {
                    const user = response.data;
                    console.log("User data:", user); // Debug
                    
                    // Fill form fields
                    $('#editUserId').val(user.Id);
                    $('#editPBNumber').val(user.PBNum || '');
                    $('#editMemberID').val(user.MemberID || '');
                    $('#editName').val(user.Name || '');
                    $('#editCommittee').val(user.Committee_Id || '');

                    // Display profile image
                    if (user.Profile) {
                        $('#editProfilePreview').attr('src', 'data:image/jpeg;base64,' + user.Profile);
                    } else {
                        $('#editProfilePreview').attr('src', '../assets/images/uploadicon.png');
                    }

                    // Show modal
                    $('#editMemberModal').modal('show');
                } else {
                    toastr.error(response.message || 'Failed to load user data');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error); // Debug
                editBtn.html(originalBtnHtml).prop('disabled', false);
                toastr.error('Error loading user data: ' + error);
            }
        });
    });

    // Edit User (Submit)
    $(document).on('submit', '#editUserForm', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        console.log("Submitting edit form"); // Debugging
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                console.log("Edit response:", res); // Debugging
                if (res.success) {
                    $('#editMemberModal').modal('hide');
                    toastr.success('User updated successfully');
                    setTimeout(() => location.reload(), 500);
                } else {
                    toastr.error(res.message || 'Update failed.');
                }
                submitBtn.prop('disabled', false).html(originalText);
            },
            error: function (xhr, status, error) {
                console.error("Edit error:", error); // Debugging
                toastr.error('Server error: ' + error);
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

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Deleting...');

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

        // Reset image previews
        if ($(this).attr('id') === 'addMemberModal') {
            $('#addProfilePreview').attr('src', '../assets/images/uploadicon.png');
            $('.custom-file-label').text('Upload Picture');
        } else if ($(this).attr('id') === 'editMemberModal') {
            // Don't reset the edit preview as it might have existing image
            $('.custom-file-label').text('Upload Picture');
        }

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
            const pbNum = $(this).find('td:eq(2)').text().toLowerCase();
            const memberId = $(this).find('td:eq(3)').text().toLowerCase();
            const name = $(this).find('td:eq(4)').text().toLowerCase();

            $(this).toggle(
                pbNum.includes(filter) ||
                memberId.includes(filter) ||
                name.includes(filter)
            );
        });
    });


    // Image upload preview for Add Modal
    $(document).on('change', '#addProfileUpload', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Update file label
            $(this).next('.custom-file-label').text(file.name);
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#addProfilePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
     });
    });
    // Image upload preview for Edit Modal
    $(document).on('change', '#editProfileUpload', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Update file label
            $(this).next('.custom-file-label').text(file.name);
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editProfilePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });