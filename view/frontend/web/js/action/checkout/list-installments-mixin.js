/**
 * O2TI PagBank Discount in Instalmment.
 *
 * Copyright © 2024 O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * @license   See LICENSE for license details.
 */

define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (listInstallmentsAction) {
        return wrapper.wrap(listInstallmentsAction, function (originalAction, value) {
            var resultPromise = originalAction(value);

            return resultPromise.then(function (result) {
                if (result && result.length > 0) {
                    var firstInstallment = result[0];
                    if (firstInstallment.installment_label.includes('sem juros')) {
                        firstInstallment.installment_label = firstInstallment.installment_label.replace('sem juros', 'com desconto');
                    }
                }

                return result;
            }).catch(function (error) {
                console.error('Error in listInstallmentsAction:', error);
                throw error;
            });
            
            return result;
        });
    };
});
