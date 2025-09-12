define([
    'ko'
], function (ko) {
    'use strict';

    return function (Component) {
        return Component.extend({
            defaults: {
                vn_region: ko.observable(null)
            },

            initialize: function () {
                this._super();
                var self = this;

                this.vn_region.subscribe(function (value) {
                    if (!self.source.get('billingAddress.custom_attributes')) {
                        self.source.set('billingAddress.custom_attributes', {});
                    }
                    self.source.set('billingAddress.custom_attributes.vn_region', value);
                });

                return this;
            }
        });
    };
});
