define([
    'jquery',
    'uiRegistry',
    'ko'
], function ($, registry, ko) {
    'use strict';

    return function (Component) {
        return Component.extend({
            defaults: {
                vn_region: ko.observable(null)
            },

            initialize: function () {
                this._super();
                var self = this;

                // Khi checkout load địa chỉ có sẵn
                // var existing = this.source.get('shippingAddress.custom_attributes');
                // if (existing && existing.vn_region) {
                //     this.vn_region(existing.vn_region);
                // }
                // Bind to quote shippingAddress customAttributes
                this.vn_region.subscribe(function (value) {
                    if (!self.source.get('shippingAddress.custom_attributes')) {
                        self.source.set('shippingAddress.custom_attributes', {});
                    }
                    self.source.set('shippingAddress.custom_attributes.vn_region', value);
                });

                return this;
            },

            /**
             * Extend template to add our custom field
             */
            getTemplate: function () {
                return 'Magenest_Customer/shipping-address/vn-region';
            }
        });
    };
});
