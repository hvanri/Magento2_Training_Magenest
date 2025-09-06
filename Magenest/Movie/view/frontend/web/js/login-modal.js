define(['jquery', 'Magento_Ui/js/modal/modal'], function($, modal) {
    'use strict';

    return function(config, element) {
        // Lấy HTML từ template
        var loginModalContent = $('#login-modal-content');

        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'Login',
            modalClass: 'my-login-modal',
            buttons: []
        };

        // Khởi tạo modal
        var loginModal = loginModalContent.modal(options);

        // Click button mở modal
        $(element).on('click', function() {
            loginModal.modal('openModal');
        });

        // Xử lý submit form
        loginModalContent.on('submit', '#modal-login-form', function(e) {
            e.preventDefault();
            var username = $(this).find('[name="username"]').val();
            var password = $(this).find('[name="password"]').val();
            console.log('Username:', username, 'Password:', password);
            loginModal.modal('closeModal');
        });
    };
});
