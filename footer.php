<!-- Content will be loaded here -->
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Set initial title based on active page
    var activePage = "<?= $activePage ?? '' ?>";
    var pageTitles = {
        'admin': 'User Management',
        'deduction': 'Setup',
        'monthly': 'Monthly Allowance',
        'report': 'Reports'
    };
    
    if(activePage && pageTitles[activePage]) {
        $('#navTitle').text(pageTitles[activePage]);
    }

    // Sidebar toggle
    $('#sidebarToggle').click(function() {
        $('body').toggleClass('sidebar-collapsed');
    });

    // Handle sidebar menu clicks
    $('.sidebar .nav-link').click(function(e) {
        e.preventDefault();
        var title = $(this).data('title');
        $('#navTitle').text(title);
        
        // Load content via AJAX or redirect
        window.location.href = $(this).attr('href');
    });
});
</script>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
});
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
</script>




</body>
</html>