// Enhanced Real-time clock function with AM/PM formatting
function updateClock() {
    const now = new Date();
    const options = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    };
    
    // Format the time with proper spacing
    const formattedTime = now.toLocaleString('en-US', options)
        .replace(/,/g, '')  // Remove commas
        .replace(/\s+/g, ' '); // Normalize spaces
    
    document.getElementById('clock-text').innerText = formattedTime;
    
    // Add pulse animation every second
    const clockElement = document.getElementById('realtime-clock');
    if (clockElement) {
        clockElement.style.animation = 'none';
        void clockElement.offsetWidth; // Trigger reflow
        clockElement.style.animation = 'pulse 1s';
    }
}

// Auto-focus the search input when page loads
function autoFocusSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.focus();
        
        // If coming back from a search, select all text for easy replacement
        if (searchInput.value) {
            searchInput.select();
        }
    }
}

// Add smooth transitions for UI elements
function setupUITransitions() {
    // Button hover effects
    $('.btn-time').hover(
        function() {
            $(this).css({
                'transform': 'translateY(-2px)',
                'box-shadow': '0 4px 8px rgba(0,0,0,0.1)'
            });
        },
        function() {
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': 'none'
            });
        }
    );
    
    // Card hover effect
    $('.status-card').hover(
        function() {
            $(this).css('transform', 'scale(1.01)');
        },
        function() {
            $(this).css('transform', 'scale(1)');
        }
    );
}

// Handle form submission with feedback
function handleFormSubmission() {
    $('form').on('submit', function(e) {
        const memberIdInput = $(this).find('input[name="member_id"]');
        if (memberIdInput.length && !memberIdInput.val().trim()) {
            e.preventDefault();
            memberIdInput.addClass('is-invalid');
            setTimeout(() => memberIdInput.removeClass('is-invalid'), 2000);
        }
    });
}

// Add animation for alerts
function setupAlertAnimations() {
    $('.alert').on('close.bs.alert', function() {
        $(this).animate({ opacity: 0, height: 0 }, 300, function() {
            $(this).remove();
        });
        return false;
    });
}

// Main document ready function
$(document).ready(function() {
    // Initialize all components
    updateClock();
    autoFocusSearch();
    setupUITransitions();
    handleFormSubmission();
    setupAlertAnimations();
    
    // Update clock every second
    setInterval(updateClock, 1000);
    
    // Auto-hide alerts after 7 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 7000);
    
    // Add click sound effect for buttons (optional)
    $('.btn-time, .btn-search').on('click', function() {
        const audio = new Audio('/assets/sounds/click.mp3'); // Add this file if you want sound
        audio.volume = 0.3;
        audio.play().catch(e => console.log('Audio play failed:', e));
    });
    
    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});