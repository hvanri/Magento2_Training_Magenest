define(['jquery', 'Magento_Ui/js/modal/alert'], function($, alert) {
    'use strict';

    return function(config, element) {
        $(element).on('click', function() {
            alert({
                title: 'Notice',
                content: 'This is a Magento 2 modal alert!',
                actions: {
                    always: function() {
                        console.log('Alert closed');
                    }
                }
            });
        });
    };
});
