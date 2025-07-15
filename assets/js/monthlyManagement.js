document.addEventListener('DOMContentLoaded', function() {
    // Initialize buttons and search
    const generateBtn = document.getElementById('generateBtn');
    if (generateBtn) generateBtn.addEventListener('click', generateAllowanceTable);

    const searchInput = document.getElementById('memberSearch');
    if (searchInput) searchInput.addEventListener('input', filterMembers);

    // Modal handlers
    $('#addAllowanceModal').on('hidden.bs.modal', resetAllowanceForm);
    $('#editAllowanceModal').on('hidden.bs.modal', function() {
        $('#editAllowanceTableBody').empty();
    });

    // Form submissions
    $(document)
        .on('submit', '#editAllowanceForm', function(e) {
            e.preventDefault();
            saveAllowanceChanges();
        })
        .on('submit', '#addAllowanceForm', function(e) {
            e.preventDefault();
            submitAllowanceForm();
        });

    // Search functionality
    $('#editMemberSearch').on('input', function() {
        const search = $(this).val().toLowerCase();
        $('#editAllowanceTableBody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().includes(search));
        });
    });

    // Edit button handler
    $(document).on('click', '.edit-btn', function() {
        const dateFrom = $(this).data('date_from');
        const dateTo = $(this).data('date_to');
        fetchAllowanceData(dateFrom, dateTo);
    });

    // Delete button handler - Updated implementation
    $(document).on('click', '.delete-btn', function() {
    const allowanceId = $(this).data('id');
    const allowanceName = $(this).closest('tr').find('td:eq(1)').text().trim();
    
    deleteTargetId = allowanceId;
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    $('#deleteMonthlyModal').find('.modal-body').html(`
        <p>Are you sure you want to delete the allowance for <strong>${escapeHtml(allowanceName)}</strong>?</p>
        <input type="hidden" id="deleteCsrfToken" value="${csrfToken}">
    `);
    
    $('#deleteMonthlyModal').modal('show');
});

    // Confirm delete button handler
$('#confirmDeleteBtn').on('click', function() {
    if (!deleteTargetId) return;
    const csrfToken = $('#deleteCsrfToken').val();
    const btn = $(this);
    const originalText = btn.html();
    
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');

    $.ajax({
        url: '../logic/MonthlyManagement/deleteMonthlyLogic.php',
        type: 'POST',
        data: {
            id: deleteTargetId,
            csrf_token: csrfToken
        },
        success: function(response) {
            try {
                const data = parseResponse(response);
                if (data.success) {
                    toastr.success(data.toast?.message || 'Deleted', data.toast?.title || 'Success');
                    $('#deleteMonthlyModal').modal('hide');
                    
                    // Immediately remove the deleted row from the table
                    $(`[data-id="${deleteTargetId}"]`).closest('tr').fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if table is now empty
                        if ($('#allowanceTableBody tr').length === 0) {
                            $('#allowanceTableBody').html('<tr><td colspan="10" class="text-center">No records found</td></tr>');
                        }
                    });
                    
                    // Clear the date inputs if needed
                    $('#modal_date_from, #modal_date_to, #form_date_from, #form_date_to').val('');
                } else {
                    showError(data);
                }
            } catch (e) {
                handleInvalidResponse();
            }
        },
        error: handleAjaxError,
        complete: () => {
            btn.prop('disabled', false).html(originalText);
            deleteTargetId = null;
        }
    });
});
});

// GLOBAL VARIABLE for delete target
let deleteTargetId = null;

// ========== UTILITY FUNCTIONS ==========
function formatDateForAPI(dateString) {
    if (!dateString) return '';
    const parts = dateString.split('/');
    if (parts.length === 3) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }
    return dateString;
}

function formatDisplayDate(apiDate) {
    if (!apiDate) return '';
    const parts = apiDate.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return apiDate;
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

function loadingSpinner() {
    return '<tr><td colspan="10" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
}

// ========== DATA FETCHING ==========
function fetchAllowanceData(dateFrom, dateTo) {
    $('#editAllowanceModal').modal('show');
    $('#editAllowanceTableBody').html(loadingSpinner());

    const formattedFrom = formatDateForAPI(dateFrom);
    const formattedTo = formatDateForAPI(dateTo);

    $.ajax({
        url: `../logic/MonthlyManagement/editMonthlyLogic.php?date_from=${formattedFrom}&date_to=${formattedTo}`,
        type: 'GET',
        success: function(response) {
            try {
                const data = parseResponse(response);
                
                if (data.success) {
                    renderEditAllowanceTable(data);
                    $('#editDateFrom').val(data.date_from);
                    $('#editDateTo').val(data.date_to);
                    $('#editDateFromDisplay').val(formatDisplayDate(data.date_from));
                    $('#editDateToDisplay').val(formatDisplayDate(data.date_to));
                    toastr.success('Allowance data loaded', 'Success');
                } else {
                    showError(data);
                    $('#editAllowanceModal').modal('hide');
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                toastr.error('Error processing data');
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr.responseText);
            toastr.error('Failed to load allowance data');
        }
    });
}

// ========== TABLE RENDERING ==========
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
                <td>
                    <input type="number" name="duty_hours[]" class="form-control form-control-sm bg-light" 
                           value="${user.HoursWorked || 0}" readonly>
                    <input type="hidden" name="hours_worked[]" value="${user.HoursWorked || 0}">
                </td>
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
                <input type="hidden" name="allowance_id[]" value="${user.allowance_id}">
            </tr>
        `);
    });

    $('.deduction-input, [name="transpo_allowance[]"]').on('input', function() {
    const row = $(this).closest('tr');
    const transpo = parseFloat(row.find('[name="transpo_allowance[]"]').val()) || 0;
    
    // Only include deductions with values > 0
    const deductions = row.find('.deduction-input').get().reduce((sum, input) => {
        const val = parseFloat(input.value) || 0;
        return val > 0 ? sum + val : sum;
    }, 0);
    
    row.find('.savings-cell').text((transpo - deductions).toFixed(2));
});
}

function createDeductionSubheader(deductionTypes) {
    return `<tr><td colspan="6"></td>${
        deductionTypes.map(d => `<th>${escapeHtml(d.DeductionType)}</th>`).join('')
    }<td></td></tr>`;
}

function calculateUserSavings(user) {
    const transpo = user.TranspoAllowance || 0;
    const deductions = user.Deductions ? Object.values(user.Deductions).reduce((sum, d) => sum + (d.Amount || 0), 0) : 0;
    return (transpo - deductions).toFixed(2);
}

// Update the savings calculation event handler
$('.deduction-input, [name="transpo_allowance[]"]').on('input', function() {
    const row = $(this).closest('tr');
    const transpo = parseFloat(row.find('[name="transpo_allowance[]"]').val()) || 0;
    const deductions = row.find('.deduction-input').get().reduce((sum, input) => {
        return sum + (parseFloat(input.value) || 0);
    }, 0);
    row.find('.savings-cell').text((transpo - deductions).toFixed(2));
});

// ========== FORM HANDLING ==========
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
        success: function(response) {
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

// ========== ERROR HANDLING ==========
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

function handleAllowanceAdded(response) {
    try {
        const data = parseResponse(response);
        if (data.success) {
            toastr.success(data.message, 'Success');
            $('#addAllowanceModal').modal('hide');
            resetAllowanceForm();
            fetchAndUpdateTableData();
        } else {
            showError(data);
        }
    } catch (e) {
        handleInvalidResponse();
    }
}

function fetchAndUpdateTableData() {
    const dateFrom = $('#date_from').val();
    const dateTo = $('#date_to').val();
    
    $.ajax({
        url: window.location.pathname,
        method: 'GET',
        data: { 
            date_from: dateFrom, 
            date_to: dateTo,
            ajax: true
        },
        success: function(response) {
            const $newContent = $(response).find('#allowanceTable tbody');
            $('#allowanceTable tbody').html($newContent.html());
            
            const $newPagination = $(response).find('.pagination-container');
            $('.pagination-container').replaceWith($newPagination);
        },
        error: function(xhr) {
            console.error('Error refreshing table:', xhr.responseText);
            toastr.error('Failed to refresh table data');
        }
    });
}

// ========== OTHER FUNCTIONS ==========
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
        success: function(response) {
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

function renderAllowanceTable(data) {
    const tbody = $('#allowanceTableBody');
    tbody.empty();

    const deductionsHeader = $('#deductions-header');
    deductionsHeader.attr('colspan', data.deductionTypes.length);

    if (data.deductionTypes.length > 0) {
        tbody.append(createDeductionSubheader(data.deductionTypes));
    }

    data.users.forEach(user => {
        tbody.append(`
            <tr class="allowance-user-row">
                <td>${escapeHtml(user.Committee || '')}</td>
                <td>${escapeHtml(user.Name)}<input type="hidden" name="user_id[]" value="${user.id}"></td>
                <td>${escapeHtml(user.MemberID || '')}</td>
                <td>${user.HoursWorked || 0}</td>
                <td><input type="number" name="rate[]" class="form-control" value="${user.Rate || 0}"></td>
                <td><input type="number" name="transpo_allowance[]" class="form-control" value="${user.TranspoAllowance || 0}"></td>
                ${data.deductionTypes.map(d => `
                    <td>
                        <input type="number" name="deduction_amount[${user.id}][${d.Id}]" 
                               class="form-control form-control-sm deduction-input" 
                               value="0" min="0">
                    </td>
                `).join('')}
                <td class="savings-cell">${user.TranspoAllowance || 0}</td>
            </tr>
        `);
    });

    $('.deduction-input, [name="transpo_allowance[]"]').on('input', calculateSavings);
}

function calculateSavings() {
    $('tr.allowance-user-row').each(function() {
        const row = $(this);
        const transpo = parseFloat(row.find('[name="transpo_allowance[]"]').val()) || 0;
        const deductions = row.find('.deduction-input').get().reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
        row.find('.savings-cell').text((transpo - deductions).toFixed(2));
    });
}

function filterMembers() {
    const search = $('#memberSearch').val().toLowerCase();
    $('tr.allowance-user-row').each(function() {
        $(this).toggle($(this).text().toLowerCase().includes(search));
    });
}

function refreshAllowanceTable() {
    // ✅ Clear the table visually first
    $('#allowanceTableBody').empty();
    $('#allowance-section').hide();

    // ✅ Clear the search field if needed
    $('#memberSearch').val('');

    // ✅ Get date range and re-generate if valid
    const from = $('#form_date_from').val();
    const to = $('#form_date_to').val();

    if (from && to) {
        generateAllowanceTable();
    }
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

//  MODAL COLUMN SIZES   ----Add this function to your existing JS
function adjustTableColumns() {
    const tables = ['#allowanceTable', '#editAllowanceTable'];
    
    tables.forEach(tableId => {
        const table = $(tableId);
        if (table.length) {
            // Calculate available width for deductions
            const fixedColumnsWidth = table.find('th:not([colspan])').get().reduce((sum, th) => {
                return sum + $(th).outerWidth();
            }, 0);
            
            const tableWidth = table.width();
            const availableWidth = tableWidth - fixedColumnsWidth;
            const deductionCount = table.find('th[colspan]').attr('colspan') || 0;
            const deductionWidth = Math.max(80, availableWidth / deductionCount);
            
            // Apply widths to deduction columns
            table.find('th[colspan]').each(function() {
                const colspan = parseInt($(this).attr('colspan'));
                $(this).width(deductionWidth * colspan);
            });
            
            table.find('.deduction-input').each(function() {
                $(this).css('max-width', `${deductionWidth}px`);
            });
        }
    });
}

// Call this function when the modal is shown and when window is resized
$(document).on('shown.bs.modal', '#addAllowanceModal, #editAllowanceModal', function() {
    adjustTableColumns();
    $(window).on('resize', adjustTableColumns);
});

$(document).on('hidden.bs.modal', '#addAllowanceModal, #editAllowanceModal', function() {
    $(window).off('resize', adjustTableColumns);
});