/**
 * O2TI PagBank Discount in Instalmment.
 *
 * Copyright © 2024 O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * @license   See LICENSE for license details.
 */

var config = {
    config: {
        mixins: {
            'PagBank_PaymentMagento/js/action/checkout/list-installments': {
                'O2TI_PagBankDiscountInInstallment/js/action/checkout/list-installments-mixin': true
            }
        }
    }
};