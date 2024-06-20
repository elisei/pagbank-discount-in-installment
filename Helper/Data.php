<?php
/**
 * O2TI PagBank Discount in Instalmment.
 *
 * Copyright © 2024 O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * @license   See LICENSE for license details.
 */

namespace O2TI\PagBankDiscountInInstallment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Helper para configurações de desconto.
 */
class Data extends AbstractHelper
{
    /**
     * Config path para o percentual de desconto na primeira parcela.
     */
    public const XML_PATH_FIRST_INSTALLMENT_DISCOUNT = 'payment/pagbank_paymentmagento/first_installment_discount';

    /**
     * Obter percentual de desconto na primeira parcela.
     *
     * @param int|null $storeId
     * @return float
     */
    public function getFirstInstallmentDiscount($storeId = null)
    {
        return (float) $this->scopeConfig->getValue(
            self::XML_PATH_FIRST_INSTALLMENT_DISCOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
