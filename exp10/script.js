const form = document.getElementById('messageForm');
const messageField = document.getElementById('message');
const countEl = document.getElementById('charCount');

if (messageField && countEl) {
    const updateCount = () => {
        const length = messageField.value.length;
        countEl.textContent = `${length} / 500`;
    };

    messageField.addEventListener('input', updateCount);
    updateCount();
}

if (form) {
    form.addEventListener('submit', (event) => {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = messageField.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!name || !email || !message) {
            event.preventDefault();
            alert('Please fill in all fields before submitting.');
            return;
        }
        if (!emailPattern.test(email)) {
            event.preventDefault();
            alert('Please enter a valid email address.');
            return;
        }
        if (message.length > 500) {
            event.preventDefault();
            alert('Your message must be 500 characters or fewer.');
        }
    });
}
