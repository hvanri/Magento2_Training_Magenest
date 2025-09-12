var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping-address/address-renderer/default': {
                'Magenest_Customer/js/view/shipping-address/address-renderer/vn-region-mixin': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'Magenest_Customer/js/view/billing-address/vn-region-mixin': true
            }
        }
    }
};
