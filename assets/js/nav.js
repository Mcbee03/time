$(document).ready(function () {
    // Sidebar toggle for desktop and mobile
    $('#sidebarToggle').on('click', function (e) {
        e.stopPropagation();
        if ($(window).width() <= 991.98) {
            $('body').toggleClass('show-sidebar');
        } else {
            // Manual toggle: show/hide sidebar
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
            $('#sidebar').data('hovered', true);
        }
    });
    $('#sidebar').on('mouseleave', function () {
        if (
            $(window).width() > 991.98 &&
            $('#sidebar').data('hovered') === true
        ) {
            $('#sidebar').addClass('collapsed');
            $('body').addClass('sidebar-collapsed');
            $('#sidebar').data('hovered', false);
        }
    });

    // Reset hover flag when toggling manually
    $('#sidebarToggle').on('click', function () {
        $('#sidebar').data('hovered', false);
    });

    // Reset on resize
    $(window).on('resize orientationchange', function () {
        if ($(window).width() <= 991.98) {
            // Remove desktop classes and flags
            $('#sidebar').addClass('collapsed');
            $('body').addClass('sidebar-collapsed');
            $('#sidebar').data('hovered', false);
            $('#sidebar').data('manual', false);
            $('body').removeClass('show-sidebar');
        } else {
            // Remove mobile classes
            $('body').removeClass('show-sidebar');
        }
    });

    // Enable Bootstrap dropdowns
    $('.dropdown-toggle').dropdown();
});

$(function() {
    $(window).trigger('resize');
});

document.addEventListener('DOMContentLoaded', function() {
    // Handle logout dropdown item
    const logoutLink = document.querySelector('a[href*="logoutLogic.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Force a hard redirect to bypass any caching
            window.location.href = this.href + '?nocache=' + new Date().getTime();
        });
    }
});