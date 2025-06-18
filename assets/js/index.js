    // Hide messages after 3 seconds
    setTimeout(function() {
        const messageElement = document.getElementById('timed-message');
        const hoursElement = document.getElementById('total-hours-message');
        
        if (messageElement) {
            messageElement.classList.add('fade-out');
            setTimeout(() => messageElement.remove(), 500);
        }
        
        if (hoursElement) {
            hoursElement.classList.add('fade-out');
            setTimeout(() => hoursElement.remove(), 500);
        }
    }, 3000);

    // Real-time clock
    function updateClock() {
        const now = new Date();
        const formattedTime = now.toLocaleString('en-US', {
            hour: 'numeric', minute: 'numeric', second: 'numeric',
            hour12: true, month: 'short', day: 'numeric', year: 'numeric'
        });
        document.getElementById('realtime-clock').innerText = formattedTime;
    }

    setInterval(updateClock, 1000);
    updateClock();
