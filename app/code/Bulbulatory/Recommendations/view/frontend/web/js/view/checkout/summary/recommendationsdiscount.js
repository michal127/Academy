define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote'
    ],
    function ($, Component, quote) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Bulbulatory_Recommendations/checkout/summary/recommendationsdiscount'
            },
            totals: quote.getTotals(),

            isDisplayedRecommendationsDiscount: function () {
                return (this.getRecommendationsSegment() !== null);
            },
            getRecommendationsDiscountLabel: function () {
                return this.getRecommendationsSegment().title;
            },
            getRecommendationsDiscount: function () {
                return this.getFormattedPrice(this.getRecommendationsSegment().value);
            },
            getRecommendationsSegment: function () {
                var recommendationsDiscountSegments;

                if (!this.totals()) {
                    return null;
                }

                recommendationsDiscountSegments = this.totals()['total_segments'].filter(function (segment) {
                    return segment.code.indexOf('recommendations_discount') !== -1;
                });

                return recommendationsDiscountSegments.length ? recommendationsDiscountSegments[0] : null;
            },
        });
    }
);
