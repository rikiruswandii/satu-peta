import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

// Daftarkan plugin FilePond
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
);

// Pilih semua elemen input dengan class 'filepond'
const inputElements = document.querySelectorAll('input[type="file"].filepond');

// Loop melalui setiap elemen dan inisialisasi FilePond
inputElements.forEach(inputElement => {
    const existingFileUrl = inputElement.getAttribute('data-existing-file');
    const pond = FilePond.create(inputElement, {
        labelIdle: `Drag & Drop your file or <span class="filepond--label-action">Browse</span>`,
        allowMultiple: false, // Hanya satu file yang diunggah
        acceptedFileTypes: ['image/*', 'application/json'], // Hanya file gambar yang diterima
    });
    pond.setOptions({
        server: {
            url: '/filepond/api',
            process: {
                url: "/process",
                headers: (file) => {
                    // Send the original file name which will be used for chunked uploads
                    return {
                        "Upload-Name": file.name,
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    };
                },
            },
            revert: '/process',
            patch: "?patch=",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });

    // Jika ada file lama, tampilkan di FilePond
    if (existingFileUrl) {
        pond.files = [{
            source: existingFileUrl,
            options: {
                type: 'public',
            }
        }];
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
