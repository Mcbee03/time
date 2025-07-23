// Real-time clock with formatting
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
    const formattedTime = now.toLocaleString('en-US', options)
        .replace(/,/g, '')
        .replace(/\s+/g, ' ');
    document.getElementById('clock-text').innerText = formattedTime;

    const clockElement = document.getElementById('realtime-clock');
    if (clockElement) {
        clockElement.style.animation = 'none';
        void clockElement.offsetWidth;
        clockElement.style.animation = 'pulse 1s';
    }
}

// Focus search input
function autoFocusSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.focus();
        if (searchInput.value) {
            searchInput.select();
        }
    }
}

// Focus Time In/Out button
function autoFocusTimeButton() {
    const timeButton = document.querySelector('.btn-time');
    if (timeButton) {
        timeButton.focus();
        timeButton.classList.add('focused-keyboard');

        // Pressing Enter will click the button
        timeButton.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                timeButton.click();
            }
        });

        // Also bind Enter to document if button is focused
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && document.activeElement === timeButton) {
                e.preventDefault();
                timeButton.click();
            }
        });
    }
}


// Hover effects
function setupUITransitions() {
    $('.btn-time').hover(
        function () {
            $(this).css({
                'transform': 'translateY(-2px)',
                'box-shadow': '0 4px 8px rgba(0,0,0,0.1)'
            });
        },
        function () {
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': 'none'
            });
        }
    );

    $('.status-card').hover(
        function () {
            $(this).css('transform', 'scale(1.01)');
        },
        function () {
            $(this).css('transform', 'scale(1)');
        }
    );
}

// Form validation
function handleFormSubmission() {
    $('form').on('submit', function (e) {
        const memberIdInput = $(this).find('input[name="member_id"]');
        if (memberIdInput.length && !memberIdInput.val().trim()) {
            e.preventDefault();
            memberIdInput.addClass('is-invalid');
            setTimeout(() => memberIdInput.removeClass('is-invalid'), 2000);
        }
    });
}

// Alert animation
function setupAlertAnimations() {
    $('.alert').on('close.bs.alert', function () {
        $(this).animate({ opacity: 0, height: 0 }, 300, function () {
            $(this).remove();
        });
        return false;
    });
}

// Run everything
$(document).ready(function () {
    updateClock();
    autoFocusSearch();
  

    setupUITransitions();
    handleFormSubmission();
    setupAlertAnimations();
    animateProfileDisplay();
    setInterval(updateClock, 1000);

    setTimeout(function () {
        $('.alert').alert('close');
    }, 7000);

    $('.btn-time, .btn-search').on('click', function () {
        const audio = new Audio('/assets/sounds/click.mp3');
        audio.volume = 0.3;
        audio.play().catch(e => console.log('Audio play failed:', e));
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});

// Add this to your existing JavaScript
function animateProfileDisplay() {
    const profileDisplay = document.querySelector('.profile-display');
    if (profileDisplay) {
        profileDisplay.style.opacity = '0';
        profileDisplay.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            profileDisplay.style.transition = 'all 0.4s ease-out';
            profileDisplay.style.opacity = '1';
            profileDisplay.style.transform = 'translateY(0)';
        }, 100);
    }
}