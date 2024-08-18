import './bootstrap';

// resources/js/custom.js
document.addEventListener('DOMContentLoaded', function () {
    fetch('/url-to-api-endpoint', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // Tambahkan header lain jika diperlukan, seperti Authorization
        },
        body: JSON.stringify({
            // Data yang dikirimkan dalam tubuh permintaan
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        // Proses data di sini
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

