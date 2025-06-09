<!-- Content will be loaded here -->
</div>

<?php include 'head.php'; ?>


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
// Initialize tooltips
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>


