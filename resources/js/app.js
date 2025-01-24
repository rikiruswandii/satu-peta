import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

const inputElement = document.querySelector('input[type="file"].filepond');
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginImageEdit
);

const pond = FilePond.create(inputElement, {
    labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
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


$("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});

$("#error-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});

$("#info-alert").fadeTo(2000, 500).slideUp(500, function () {
    $(this).slideUp(500);
});
