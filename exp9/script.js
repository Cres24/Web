function appendValue(value) {
    const display = document.getElementById('display');
    display.value += value;
    display.focus();
}

function clearDisplay() {
    const display = document.getElementById('display');
    display.value = '';
    display.focus();
}

const form = document.getElementById('calcForm');
if (form) {
    form.addEventListener('submit', function (event) {
        const display = document.getElementById('display');
        if (display.value.trim() === '') {
            event.preventDefault();
            display.focus();
        }
    });
}
