// Basic interactivity for the Budget Tracker
document.addEventListener('DOMContentLoaded', () => {
    // Toggle dark mode (if implemented in future)
    const toggleDarkMode = () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    };

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Example: Add confirmation for form submissions (optional)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Are you sure you want to submit this?')) {
                e.preventDefault();
            }
        });
    });
});

// Note: Dark mode CSS would need to be added to style.css, e.g.:
// .dark-mode { background-color: #333; color: #fff; }