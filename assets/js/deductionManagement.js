$(document).ready(function () {
    // 🔍 Real-time Search
    $(document).on('keyup', '#searchInput', function () {
        const filter = $(this).val().toLowerCase().trim();
        $('#deductionTable tbody tr').each(function () {
            const deduction = $(this).find('td:eq(1)').text().toLowerCase();
            const dateFrom = $(this).find('td:eq(2)').text().toLowerCase();
            const dateTo = $(this).find('td:eq(3)').text().toLowerCase();
            $(this).toggle(
                deduction.includes(filter) ||
                dateFrom.includes(filter) ||
                dateTo.includes(filter)
            );
        });
    });

    // ➕ ADD Deduction
    $(document).on('submit', '#addDeductionForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const original = btn.html();
        btn.prop('disabled', true).html('Adding...');

        const dateFrom = new Date(form.find('[name="date_from"]').val());
        const dateTo = new Date(form.find('[name="date_to"]').val());

        if (dateFrom > dateTo) {
            toastr.error('End date must be after start date');
            btn.prop('disabled', false).html(original);
            return;
        }

        $.post(form.attr('action'), form.serialize(), function (res) {
            if (res.success) {
                $('#addDeductionModal').one('hidden.bs.modal', function () {
                    btn.prop('disabled', false).html(original);
                    location.reload();
                }).modal('hide');
            } else {
                toastr.error(res.message || 'Failed to add deduction.');
                btn.prop('disabled', false).html(original);
            }
        }, 'json').fail(() => {
            toastr.error('Server error');
            btn.prop('disabled', false).html(original);
        });
    });

    // ✏️ Load to Edit Modal
    $(document).on('click', '.edit-btn', function () {
        $('#editDeductionId').val($(this).data('id'));
        $('#editDeductionName').val($(this).data('deduction'));
        $('#editDateFrom').val($(this).data('date_from'));
        $('#editDateTo').val($(this).data('date_to'));
        $('#editDeductionModal').modal('show');
    });

    // ✏️ Submit Edit
    $(document).on('submit', '#editDeductionForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const original = btn.html();
        btn.prop('disabled', true).html('Updating...');

        const dateFrom = new Date(form.find('[name="date_from"]').val());
        const dateTo = new Date(form.find('[name="date_to"]').val());

        if (dateFrom > dateTo) {
            toastr.error('End date must be after start date');
            btn.prop('disabled', false).html(original);
            return;
        }

        $.post(form.attr('action'), form.serialize(), function (res) {
            if (res.success) {
                $('#editDeductionModal').one('hidden.bs.modal', function () {
                    btn.prop('disabled', false).html(original);
                    location.reload();
                }).modal('hide');
            } else {
                toastr.error(res.message || 'Update failed.');
                btn.prop('disabled', false).html(original);
            }
        }, 'json').fail(() => {
            toastr.error('Server error');
            btn.prop('disabled', false).html(original);
        });
    });

    // 🗑️ Load Delete Modal
    $(document).on('click', '.delete-btn', function () {
        $('#deleteId').val($(this).data('id'));
        $('#deleteDeductionName').text($(this).data('deduction') || 'this deduction');
        $('#deleteDeductionModal').modal('show');
    });

    // 🗑️ Submit Delete
    $(document).on('submit', '#deleteForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const original = btn.html();
        btn.prop('disabled', true).html('Deleting...');

        $.post(form.attr('action'), form.serialize(), function (res) {
            $('#deleteDeductionModal').one('hidden.bs.modal', function () {
                btn.prop('disabled', false).html(original);
                location.reload();
            }).modal('hide');
        }, 'json').fail(() => {
            toastr.error('Server error');
            btn.prop('disabled', false).html(original);
        });
    });

    // ✅ FIX MODAL FREEZE — RESET FORM ON CLOSE
    $('#addDeductionModal, #editDeductionModal, #deleteDeductionModal').on('hidden.bs.modal', function () {
        const $modal = $(this);
        const form = $modal.find('form')[0];
        if (form) form.reset();

        const btn = $modal.find('button[type="submit"]');
        if (btn.length) {
            const btnId = btn.attr('id');
            let text = 'Submit';
            if (btnId === 'editDeductionBtn') text = 'Update Deduction';
            if (btnId === 'confirmDeleteBtn') text = 'Delete';
            if (btnId === 'addDeductionBtn') text = 'Add Deduction';
            btn.prop('disabled', false).html(text);
        }

        // 🧼 Safety Cleanup
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
});
