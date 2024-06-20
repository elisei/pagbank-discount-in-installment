<?php
/**
 * O2TI PagBank Discount in Instalmment.
 *
 * Copyright © 2024 O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * @license   See LICENSE for license details.
 */

namespace O2TI\PagBankDiscountInInstallment\Plugin\PagBank\Model\Api;

use Magento\Checkout\Model\Session as CheckoutSession;
use O2TI\PagBankDiscountInInstallment\Helper\Data as DiscountHelper;
use PagBank\PaymentMagento\Model\Api\ConsultPSInstallments;

/**
 * Plugin para alterar o valor da primeira parcela e implementar desconto.
 */
class ConsultPSInstallmentsPlugin
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var DiscountHelper
     */
    protected $discountHelper;

    /**
     * ConsultPSInstallmentsPlugin constructor.
     *
     * @param CheckoutSession $checkoutSession
     * @param DiscountHelper $discountHelper
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        DiscountHelper $discountHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->discountHelper = $discountHelper;
    }

    /**
     * Around plugin for getPagBankInstallments method
     *
     * @param ConsultPSInstallments $subject
     * @param callable $proceed
     * @param int|null $storeId
     * @param string $creditCardBin
     * @param string $amount
     * @return array
     */
    public function aroundGetPagBankInstallments(
        ConsultPSInstallments $subject,
        callable $proceed,
        $storeId,
        $creditCardBin,
        $amount
    ) {
        $installments = $proceed($storeId, $creditCardBin, $amount);

        // $quote = $this->checkoutSession->getQuote();
        // $couponCode = $quote->getCouponCode();

        $discountPercentage = $this->discountHelper->getFirstInstallmentDiscount($storeId);

        if ($discountPercentage > 0) {
            if (!empty($installments) && isset($installments[0]['installment_value']) && isset($installments[0]['amount']['value'])) {
                $originalInstallmentValue = $installments[0]['installment_value'];
                $originalAmountValue = $installments[0]['amount']['value'];

                $discountedInstallmentValue = $originalInstallmentValue * (1 - $discountPercentage / 100);
                $discountedAmountValue = $originalAmountValue * (1 - $discountPercentage / 100);

                $installments[0]['installment_value'] = round($discountedInstallmentValue, 2);
                $installments[0]['amount']['value'] = round($discountedAmountValue, 2);
                $installments[0]['amount']['has_discount'] = true;
                $installments[0]['amount']['discount_percent'] = $discountPercentage;

                $installments[0]['amount']['fees']['buyer']['interest']['total'] = ($originalInstallmentValue - $discountedInstallmentValue) * -1;
            }
        }
        
        return $installments;
    }
}
