document.addEventListener('DOMContentLoaded', function () {
    const generateBtn = document.getElementById('generateBtn');
    if (generateBtn) generateBtn.addEventListener('click', generateAllowanceTable);

    const searchInput = document.getElementById('memberSearch');
    if (searchInput) searchInput.addEventListener('input', filterMembers);

    $('#addAllowanceModal').on('hidden.bs.modal', resetAllowanceForm);
    $('#editAllowanceModal').on('hidden.bs.modal', function() {
        $('#editAllowanceTableBody').empty();
    });

    $(document)
        .on('click', '.edit-btn', function () {
            fetchAllowanceData($(this).data('id'));
        })
        .on('click', '.delete-btn', function () {
            confirmDeleteAllowance($(this).data('id'));
        })
        .on('submit', '#editAllowanceForm', function (e) {
            e.preventDefault();
            saveAllowanceChanges();
        })
        .on('submit', '#addAllowanceForm', function (e) {
            e.preventDefault();
            submitAllowanceForm();
        });

    $('#editMemberSearch').on('input', function () {
        const search = $(this).val().toLowerCase();
        $('#editAllowanceTableBody tr').each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(search));
        });
    });
});

// === FORM SUBMISSION ===
function submitAllowanceForm() {
    const form = $('#addAllowanceForm');
    const submitBtn = form.find('[type="submit"]');
    const originalText = submitBtn.html();

    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: handleAllowanceAdded,
        error: handleAjaxError,
        complete: () => {
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

function saveAllowanceChanges() {
    const form = $('#editAllowanceForm');
    const submitBtn = form.find('[type="submit"]');
    const originalText = submitBtn.html();

    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (response) {
            try {
                const data = parseResponse(response);
                if (data.success) {
                    toastr.success(data.message, 'Success');
                    $('#editAllowanceModal').modal('hide');
                    refreshAllowanceTable();
                } else {
                    showError(data);
                }
            } catch (e) {
                handleInvalidResponse();
            }
        },
        error: handleAjaxError,
        complete: () => {
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

// === DATA FETCHING ===
function fetchAllowanceData(allowanceId) {
    $('#editAllowanceModal').modal('show');
    $('#editAllowanceTableBody').html(loadingSpinner());

    $.ajax({
        url: `../logic/MonthlyManagement/editMonthlyLogic.php?id=${allowanceId}`,
        type: 'GET',
        success: function(data) {
            if (data.success) {
                renderEditAllowanceTable(data);
                $('#editAllowanceId').val(allowanceId);
                $('#editDateFrom').val(data.date_from);
                $('#editDateTo').val(data.date_to);
                toastr.success('Allowance data loaded', 'Success');
            } else {
                showError(data);
                $('#editAllowanceModal').modal('hide');
            }
        },
        error: handleAjaxError
    });
}

function generateAllowanceTable() {
    const from = $('#modal_date_from').val();
    const to = $('#modal_date_to').val();

    if (!from || !to) {
        toastr.warning('Please select date range', 'Validation Error');
        return;
    }

    $('#form_date_from').val(from);
    $('#form_date_to').val(to);

    const btn = $('#generateBtn');
    btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

    $('#allowanceTableBody').html(loadingSpinner());

    $.ajax({
        url: '../logic/MonthlyManagement/getMonthlyData.php',
        method: 'POST',
        data: { date_from: from, date_to: to },
        success: function (response) {
            try {
                const data = parseResponse(response);
                if (data.success) {
                    renderAllowanceTable(data);
                    $('#allowance-section').show();
                    toastr.success('Allowance data loaded', 'Success');
                } else {
                    showError(data);
                }
            } catch (e) {
                handleInvalidResponse();
            }
        },
        error: handleAjaxError,
        complete: () => {
            btn.html('<i class="fas fa-plus-circle mr-1"></i> Generate').prop('disabled', false);
        }
    });
}

// === TABLE RENDERING ===
function renderAllowanceTable(data) {
    const tbody = $('#allowanceTableBody');
    tbody.empty();

    const deductionsHeader = $('#deductions-header');
    deductionsHeader.attr('colspan', data.deductionTypes.length);

    if (data.deductionTypes.length > 0) {
        tbody.append(createDeductionSubheader(data.deductionTypes));
    }

    // Sort users by committee name
    data.users.sort((a, b) => (a.Committee || '').localeCompare(b.Committee || ''));

    // Flat render of all users
    data.users.forEach(user => {
        tbody.append(createUserRow(user, data.deductionTypes));
    });

    $('.deduction-input, [name="transpo_allowance[]"]').on('input', calculateSavings);
}

function renderEditAllowanceTable(data) {
    const tbody = $('#editAllowanceTableBody');
    tbody.empty();

    $('#editDeductionsHeader').attr('colspan', data.deductionTypes.length);

    if (data.deductionTypes.length > 0) {
        tbody.append(createDeductionSubheader(data.deductionTypes));
    }

    data.users.forEach(user => {
        tbody.append(`
            <tr>
                <td><input type="text" class="form-control form-control-sm bg-light" value="${escapeHtml(user.Committee || '')}" readonly></td>
                <td>${escapeHtml(user.Name)}<input type="hidden" name="user_id[]" value="${user.id}"></td>
                <td><input type="text" class="form-control form-control-sm bg-light" value="${escapeHtml(user.MemberID || '')}" readonly></td>
                <td><input type="number" name="duty_hours[]" class="form-control form-control-sm" value="${user.HoursWorked || 0}" required></td>
                <td><input type="number" name="rate[]" class="form-control form-control-sm" value="${user.Rate || 0}" required></td>
                <td><input type="number" name="transpo_allowance[]" class="form-control form-control-sm" value="${user.TranspoAllowance || 0}" required></td>
                ${data.deductionTypes.map(d => `
                    <td>
                        <input type="number" name="deduction_amount[${user.id}][${d.Id}]" 
                               class="form-control form-control-sm deduction-input" 
                               value="${user.Deductions?.[d.Id]?.Amount || 0}" 
                               min="0">
                    </td>
                `).join('')}
                <td class="savings-cell">${calculateUserSavings(user)}</td>
            </tr>
        `);
    });

    $('.deduction-input, [name="transpo_allowance[]"]').on('input', function() {
        const row = $(this).closest('tr');
        const transpo = parseFloat(row.find('[name="transpo_allowance[]"]').val()) || 0;
        const deductions = row.find('.deduction-input').get().reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
        row.find('.savings-cell').text((transpo - deductions).toFixed(2));
    });
}

// === HELPERS ===
function createDeductionSubheader(deductionTypes) {
    return `<tr><td colspan="6"></td>${
        deductionTypes.map(d => `<th>${escapeHtml(d.DeductionType)}</th>`).join('')
    }<td></td></tr>`;
}

function createUserRow(user, deductionTypes) {
    return `<tr class="allowance-user-row">
        <td>${escapeHtml(user.Committee || '')}</td>
        <td>${escapeHtml(user.Name)}<input type="hidden" name="user_id[]" value="${user.id}"></td>
        <td>${escapeHtml(user.MemberID || '')}</td>
        <td>${user.HoursWorked || 0}</td>
        <td><input type="number" name="rate[]" class="form-control" value="${user.Rate || 0}"></td>
        <td><input type="number" name="transpo_allowance[]" class="form-control" value="${user.TranspoAllowance || 0}"></td>
        ${deductionTypes.map(d => `
            <td>
                <input type="number" name="deduction_amount[${user.id}][${d.Id}]" 
                       class="form-control form-control-sm deduction-input" 
                       value="0" min="0">
            </td>
        `).join('')}
        <td class="savings-cell">${user.TranspoAllowance || 0}</td>
    </tr>`;
}

function calculateSavings() {
    $('tr.allowance-user-row').each(function() {
        const row = $(this);
        const transpo = parseFloat(row.find('[name="transpo_allowance[]"]').val()) || 0;
        const deductions = row.find('.deduction-input').get().reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
        row.find('.savings-cell').text((transpo - deductions).toFixed(2));
    });
}

function calculateUserSavings(user) {
    const transpo = user.TranspoAllowance || 0;
    const deductions = user.Deductions ? Object.values(user.Deductions).reduce((sum, d) => sum + (d.Amount || 0), 0) : 0;
    return (transpo - deductions).toFixed(2);
}

function filterMembers() {
    const search = $('#memberSearch').val().toLowerCase();
    $('tr.allowance-user-row').each(function() {
        $(this).toggle($(this).text().toLowerCase().includes(search));
    });
}

function refreshAllowanceTable() {
    const from = $('#form_date_from').val();
    const to = $('#form_date_to').val();
    if (from && to) generateAllowanceTable();
}

function resetAllowanceForm() {
    $('#modal_date_from, #modal_date_to, #form_date_from, #form_date_to').val('');
    $('#allowance-section').hide();
    $('#allowanceTableBody').empty();
    const generateBtn = $('#generateBtn');
    if (generateBtn.length) {
        generateBtn.show().html('<i class="fas fa-plus-circle mr-1"></i> Generate').prop('disabled', false);
    }
}

// === ERROR HANDLING ===
function handleAllowanceAdded(response) {
    try {
        const data = parseResponse(response);
        if (data.success) {
            toastr.success(data.message, 'Success');
            $('#addAllowanceModal').modal('hide');
            resetAllowanceForm();
            refreshAllowanceTable();
        } else {
            showError(data);
        }
    } catch (e) {
        handleInvalidResponse();
    }
}

function confirmDeleteAllowance(allowanceId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteAllowance(allowanceId);
        }
    });
}

function deleteAllowance(allowanceId) {
    $.ajax({
        url: '../logic/MonthlyManagement/deleteMonthlyLogic.php',
        type: 'POST',
        data: {
            id: allowanceId,
            csrf_token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            try {
                const data = parseResponse(response);
                if (data.success) {
                    toastr.success(data.toast?.message || 'Allowance deleted successfully', data.toast?.title || 'Success');
                    refreshAllowanceTable();
                } else {
                    showError(data);
                }
            } catch (e) {
                handleInvalidResponse();
            }
        },
        error: handleAjaxError
    });
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function parseResponse(response) {
    return typeof response === 'string' ? JSON.parse(response) : response;
}

function showError(data) {
    if (data.toast) {
        toastr[data.toast.type || 'error'](data.toast.message, data.toast.title);
    } else {
        toastr.error(data.message || 'Operation failed');
    }
}

function handleInvalidResponse() {
    toastr.error('Invalid server response');
}

function handleAjaxError(xhr) {
    try {
        const error = JSON.parse(xhr.responseText);
        showError(error);
    } catch {
        toastr.error('An error occurred while processing your request');
    }
}

function loadingSpinner() {
    return '<tr><td colspan="10" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
}