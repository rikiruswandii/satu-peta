import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const inputElement = document.querySelector('input[type="file"].filepond');
    if (inputElement) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        FilePond.create(inputElement).setOptions({
            server: {
                process: './uploads/process',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            }
        });
    }
});


$("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});

$("#error-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});

$("#info-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});
