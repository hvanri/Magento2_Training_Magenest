define([
    'Magento_Ui/js/form/element/file-uploader'
], function (FileUploader) {
    'use strict';

    return FileUploader.extend({
        defaults: {
            previewTmpl: 'Magenest_Movie/form/element/avatar-preview',
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            maxFileSize: 2097152 // 2MB
        }
    });
});
