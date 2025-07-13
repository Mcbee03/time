
document.addEventListener('DOMContentLoaded', function() {
    const reportType = document.getElementById('reportType');
    const memberIdInput = document.getElementById('memberId');

    reportType.addEventListener('change', function() {
        if (this.value === 'monthly') {
            // Disable member ID input for monthly reports
            memberIdInput.disabled = true;
            memberIdInput.required = false;
            memberIdInput.value = '';
        } else if (this.value === 'dtr') {
            // Enable member ID input for DTR reports
            memberIdInput.disabled = false;
            memberIdInput.required = false; // Not required for DTR as it can be for all officers
        }
    });

    // Trigger change event on page load if a value is already selected
    if (reportType.value) {
        reportType.dispatchEvent(new Event('change'));
    }
});