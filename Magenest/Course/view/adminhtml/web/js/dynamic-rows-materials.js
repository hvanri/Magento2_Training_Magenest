define([
    'Magento_Ui/js/dynamic-rows/dynamic-rows',
    'underscore'
], function (DynamicRows, _) {
    'use strict';

    return DynamicRows.extend({

        /**
         * Initialize component
         */
        initialize: function () {
            this._super();
            this.observe(['recordData']);
            return this;
        },

        /**
         * Add child component
         */
        addChild: function (ctx, index, prop) {
            var recordData = ctx || {};
            var materialType = recordData.material_type || 'text_code';

            // Call parent addChild
            this._super(recordData, index, prop);

            // Sau khi thêm child, ẩn/hiện các fields theo material type
            setTimeout(_.bind(function() {
                this.toggleFieldsByType(materialType, this.elems().length - 1);
            }, this), 100);

            return this;
        },

        /**
         * Toggle fields visibility based on material type
         */
        toggleFieldsByType: function (materialType, rowIndex) {
            var row = this.elems()[rowIndex];
            if (!row || !row.elems) {
                return;
            }

            var fields = {
                game_code: row.getChild('game_code'),
                additional_comments: row.getChild('additional_comments'),
                image_file: row.getChild('image_file'),
                file_upload: row.getChild('file_upload')
            };

            // Ẩn tất cả fields trước
            _.each(fields, function(field) {
                if (field) {
                    field.visible(false);
                }
            });

            // Hiển thị fields theo material type
            switch (materialType) {
                case 'text_code':
                    if (fields.game_code) fields.game_code.visible(true);
                    if (fields.additional_comments) fields.additional_comments.visible(true);
                    break;

                case 'image_file':
                    if (fields.additional_comments) fields.additional_comments.visible(true);
                    if (fields.image_file) fields.image_file.visible(true);
                    break;

                case 'file_zip':
                case 'file_csv':
                    if (fields.additional_comments) fields.additional_comments.visible(true);
                    if (fields.file_upload) {
                        fields.file_upload.visible(true);
                        // Set allowed extensions based on type
                        if (materialType === 'file_zip') {
                            fields.file_upload.allowedExtensions = 'zip';
                        } else if (materialType === 'file_csv') {
                            fields.file_upload.allowedExtensions = 'csv';
                        }
                    }
                    break;
            }
        },

        /**
         * Process data before send
         */
        processingAddChild: function (ctx, index, prop) {
            if (_.isObject(ctx)) {
                ctx = this.createInstance(ctx);
            }

            // Gọi parent method
            this._super(ctx, index, prop);
        }
    });
});
