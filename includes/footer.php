<!-- JS dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    let sidebarHoverExpand = false;
    let sidebarMenuClicked = false;

    // Sidebar toggle for desktop and mobile
    $('#sidebarToggle').on('click', function (e) {
        e.stopPropagation();
        if ($(window).width() <= 991.98) {
            $('body').toggleClass('show-sidebar');
        } else {
            $('#sidebar').toggleClass('collapsed');
            $('body').toggleClass('sidebar-collapsed');
        }
    });

    // Auto-collapse sidebar when clicking outside (mobile only)
    $(document).on('click touchstart', function (e) {
        if ($(window).width() <= 991.98) {
            if (
                $('body').hasClass('show-sidebar') &&
                !$(e.target).closest('#sidebar, #sidebarToggle').length
            ) {
                $('body').removeClass('show-sidebar');
            }
        }
    });

    // Desktop: auto-show sidebar on mouseenter, auto-collapse on mouseleave
    $('#sidebar').on('mouseenter', function () {
        if (
            $(window).width() > 991.98 &&
            $('#sidebar').hasClass('collapsed')
        ) {
            $('#sidebar').removeClass('collapsed');
            $('body').removeClass('sidebar-collapsed');
            sidebarHoverExpand = true;
            sidebarMenuClicked = false; // reset on new hover
        }
    });
    $('#sidebar').on('mouseleave', function () {
        if (
            $(window).width() > 991.98 &&
            (sidebarHoverExpand || sidebarMenuClicked)
        ) {
            $('#sidebar').addClass('collapsed');
            $('body').addClass('sidebar-collapsed');
            sidebarHoverExpand = false;
            sidebarMenuClicked = false;
        }
    });

    // When clicking a menu item while hover-expanded, collapse sidebar after click
    $('#sidebar .nav-link').on('click', function (e) {
        if (
            $(window).width() > 991.98 &&
            sidebarHoverExpand
        ) {
            e.preventDefault(); // Prevent instant navigation
            let link = $(this).attr('href');
            $('#sidebar').addClass('collapsed');
            $('body').addClass('sidebar-collapsed');
            sidebarHoverExpand = false;
            sidebarMenuClicked = false;
            setTimeout(function () {
                window.location.href = link;
            }, 150); // Adjust delay as needed for animation
        }
    });

    // Reset hover flag when toggling manually
    $('#sidebarToggle').on('click', function () {
        sidebarHoverExpand = false;
        sidebarMenuClicked = false;
    });

    // Reset on resize
    $(window).on('resize orientationchange', function () {
        if ($(window).width() <= 991.98) {
            $('#sidebar').addClass('collapsed');
            $('body').addClass('sidebar-collapsed');
            sidebarHoverExpand = false;
            sidebarMenuClicked = false;
            $('#sidebar').data('manual', false);
            $('body').removeClass('show-sidebar');
        } else {
            $('body').removeClass('show-sidebar');
        }
    });

    // Enable Bootstrap dropdowns
    $('.dropdown-toggle').dropdown();
});

$(function() {
    $(window).trigger('resize');
});
</script>

<!-- MODAL SCRIPT -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const generateBtn = document.querySelector('#addAllowanceModal button.btn-custom-green');
        const tbody = document.querySelector('#addAllowanceModal table tbody');

        generateBtn.addEventListener('click', function () {
            // Get values from inputs
            const dateFrom = document.getElementById('modal_date_from').value;
            const dateTo = document.getElementById('modal_date_to').value;
            const rate = document.getElementById('modal_rate').value;
            const transpo = document.getElementById('modal_transpo').value;

            if (!dateFrom || !dateTo || !rate || !transpo) {
                alert('Please fill in all fields before generating.');
                return;
            }

            // Example row - you can loop here for multiple entries
            const row = document.createElement('tr');
            row.style.backgroundColor = '#ddd';
            row.innerHTML = `
                <td>Committee A</td>
                <td>Juan Dela Cruz</td>
                <td>12345</td>
                <td><input type="number" class="form-control form-control-sm text-center" name="duty_hours[]" value=""></td>
                <td>${rate}</td>
                <td>${transpo}</td>
                <td><button type="button" class="btn btn-sm text-white" style="background-color: #2b7d62;">Input</button></td>
                <td><button type="button" class="btn btn-sm text-white" style="background-color: #2b7d62;">Input</button></td>
                <td><button type="button" class="btn btn-sm text-white" style="background-color: #2b7d62;">Input</button></td>
                <td><input type="text" class="form-control form-control-sm text-center" name="savings[]" value=""></td>
            `;

            tbody.appendChild(row);
        });
    });
</script>


<script>
function populateEditModal(data) {
  document.getElementById('editId').value = data.id;
  document.getElementById('editPbNumber').value = data.pb_number;
  document.getElementById('editMemberId').value = data.member_id;
  document.getElementById('editName').value = data.name;
  document.getElementById('editRole').value = data.role;
}
</script>



<script>
function populateEditModal(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('editPbNumber').value = item.pb_number;
    document.getElementById('editMemberId').value = item.member_id;
    document.getElementById('editName').value = item.name;
    document.getElementById('editRole').value = item.role;
}

function setDeleteMemberId(id) {
    document.getElementById('deleteMemberId').value = id;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    var memberId = document.getElementById('deleteMemberId').value;
    alert("Deleting member ID: " + memberId);
    $('#deleteConfirmModal').modal('hide');
});

// Debounce function to limit how often the search is triggered
function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(context, args);
        }, wait);
    };
}

// Auto-submit the search form on input with debounce
document.getElementById('searchInput').addEventListener('input', debounce(function() {
    document.getElementById('searchForm').submit();
}, 500)); // 500ms delay
</script>


<script>
$(document).ready(function () {
    $('.edit-btn').on('click', function () {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        var memberId = $(this).data('member-id');
        var pbNumber = $(this).data('pb-number');
        var role = $(this).data('role');
        var committee = $(this).data('committee');

        $('#edit_id').val(userId);
        $('#edit_name').val(userName);
        $('#edit_member_id').val(memberId);
        $('#edit_pb_number').val(pbNumber);
        $('#edit_role').val(role);
        $('#edit_committee').val(committee);

        $('#editUserModal').modal('show');
    });

    $('.delete-btn').on('click', function () {
        var userId = $(this).data('id');
        $('#delete_id').val(userId);
    });

    $('#addUserBtn').on('click', function () {
        const name = $('#addUserName').val().trim();
        const memberId = $('#addUserMemberId').val().trim();
        const pbNumber = $('#addUserPbNumber').val().trim();
        const committee = $('#addUserCommittee').val().trim();

        if (name && memberId && pbNumber && committee) {
            alert('User added successfully.');

            $('#addUserName').val('');
            $('#addUserMemberId').val('');
            $('#addUserPbNumber').val('');
            $('#addUserCommittee').val('');

            $('#addUserModal').modal('hide');

            location.reload();
        } else {
            alert('Please fill in all fields.');
        }
    });

    // Debounce function to limit how often the search is triggered
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    // Auto-submit the search form on input with debounce
    $('#searchInput').on('input', debounce(function() {
        $('#searchForm').submit();
    }, 500)); // 500ms delay
});
</script>