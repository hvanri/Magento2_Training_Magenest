define([
    'Magento_Ui/js/form/components/button',
    'uiRegistry',
    'underscore'
], function (Button, registry, _) {
    'use strict';

    return Button.extend({
        defaults: {
            materialType: ''
        },

        /**
         * Initialize component
         */
        initialize: function () {
            this._super();
            return this;
        },

        /**
         * Action handler for adding new material row
         */
        action: function () {
            var dynamicRows = registry.get('product_form.product_form.course-materials.course_materials_dynamic_rows');

            if (dynamicRows) {
                // Tạo data mới cho row
                var newRowData = this.generateRowData();

                // Thêm row mới
                dynamicRows.addChild(newRowData);

                // Focus vào row mới được tạo
                setTimeout(function() {
                    var newIndex = dynamicRows.elems().length - 1;
                    var newRow = dynamicRows.elems()[newIndex];
                    if (newRow && newRow.elems) {
                        var firstField = newRow.elems()[1]; // Skip material_type hidden field
                        if (firstField && firstField.focused) {
                            firstField.focused(true);
                        }
                    }
                }, 100);
            }
        },

        /**
         * Generate row data based on material type
         */
        generateRowData: function () {
            var baseData = {
                material_type: this.getMaterialType(),
                additional_comments: ''
            };

            switch (this.getMaterialType()) {
                case 'text_code':
                    baseData.game_code = '';
                    break;
                case 'image_file':
                    baseData.image_file = [];
                    break;
                case 'file_zip':
                case 'file_csv':
                    baseData.file_upload = [];
                    break;
            }

            return baseData;
        },

        /**
         * Get material type from button configuration
         */
        getMaterialType: function () {
            // Lấy material type từ button class hoặc configuration
            var buttonClasses = this.additionalClasses || this.buttonClasses || '';

            if (buttonClasses.indexOf('add-text-code-button') !== -1) {
                return 'text_code';
            } else if (buttonClasses.indexOf('import-image-button') !== -1) {
                return 'image_file';
            } else if (buttonClasses.indexOf('import-file-zip-button') !== -1) {
                return 'file_zip';
            } else if (buttonClasses.indexOf('import-file-csv-button') !== -1) {
                return 'file_csv';
            }

            return 'text_code'; // default
        }
    });
});
