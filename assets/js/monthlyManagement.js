document.addEventListener('DOMContentLoaded', function () {
    // Clear filter
    document.getElementById('clearFilterBtn').addEventListener('click', function () {
        document.querySelectorAll('.filter-input').forEach(input => input.value = '');
        this.closest('form').submit();
    });

    // Show Allowance Form
    document.getElementById('generateBtn').addEventListener('click', function () {
        const from = document.querySelector('#addAllowanceModal input[name="date_from"]').value;
        const to = document.querySelector('#addAllowanceModal input[name="date_to"]').value;
        if (!from || !to) {
            alert('Please select both Date From and Date To');
            return;
        }
        document.getElementById('allowance-section').style.display = 'block';
        this.style.display = 'none';
    });

    // Simulated form submit
    document.getElementById('addAllowanceForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Allowance data would be saved here');
    });

    // Populate Edit Modal
    $(document).on('click', '.edit-btn', function () {
        const modal = $('#editAllowanceModal');
        const dataFields = [
            'id', 'committee', 'name', 'member_id', 'date_from', 'date_to',
            'duty_hours', 'rate', 'transpo_allowance', 'rcbc', 'norf', 'rice', 'savings'
        ];

        dataFields.forEach(field => {
            modal.find(`input[name="${field === 'id' ? 'allowance_id' : `${field}[]`}]`)
                .val($(this).data(field));
        });
    });

    // Delete Modal
    $(document).on('click', '.delete-btn', function () {
        $('#delete_id').val($(this).data('id'));
        $('#delete_allowance_name').text($(this).data('name'));
    });

    // Search inside Add Modal
    $('#memberSearch').on('keyup', function () {
        const val = $(this).val().toLowerCase();
        $('#addAllowanceModal tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().includes(val));
        });
    });

    // Search inside Edit Modal
    $('#editMemberSearch').on('keyup', function () {
        const val = $(this).val().toLowerCase();
        $('#editAllowanceModal tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().includes(val));
        });
    });
});
