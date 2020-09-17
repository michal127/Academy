<?php


namespace Bulbulatory\Recommendations\Model\Total\Quote;


use Bulbulatory\Recommendations\Helper\Config;
use Bulbulatory\Recommendations\Helper\Recommendation as RecommendationHelper;
use Magento\Customer\Model\Customer;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Customer\Model\Session;

/**
 * Class RecommendationDiscount
 * @package Bulbulatory\Recommendations\Model\Total\Quote
 */
class RecommendationDiscount extends AbstractTotal
{
    /**
     * @var RecommendationHelper
     */
    private $recommendationHelper;
    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var Config
     */
    private $config;

    /**
     * RecommendationDiscount constructor.
     * @param Session $session
     * @param RecommendationHelper $recommendationHelper
     * @param Config $config
     */
    public function __construct(Session $session, RecommendationHelper $recommendationHelper, Config $config)
    {
        $this->customer = $session->getCustomer();
        $this->recommendationHelper = $recommendationHelper;
        $this->config = $config;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this|RecommendationDiscount
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        if (!$this->config->isRecommendationsModuleEnabled()) return $this;

        $confirmedRecommendations = $this->recommendationHelper->getConfirmedRecommendationsCountForCustomer($this->customer);
        $recommendationDiscount = $this->recommendationHelper->getDiscountValueForCustomer($confirmedRecommendations);
        if ($recommendationDiscount <= 0) return $this;

        $discountLabel = __('Recommendations discount') . ' -' . $recommendationDiscount . '%';
        $totalAmount = $total->getSubtotal();

        $discountAmount = "-" . ($totalAmount * $recommendationDiscount / 100);
        $appliedCartDiscount = 0;

        $discountDescription = $quote->getDiscountDescription();
        if ($discountDescription) {
            $appliedCartDiscount = $total->getDiscountAmount();
            $discountAmount = $total->getDiscountAmount() + $discountAmount;
            $discountLabel = $discountDescription . ', ' . $discountLabel;
        }

        $total->setDiscountDescription($discountLabel);
        $total->setDiscountAmount($discountAmount);
        $total->setBaseDiscountAmount($discountAmount);
        $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
        $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);

        if (isset($appliedCartDiscount)) {
            $total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
            $total->addBaseTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
        } else {
            $total->addTotalAmount($this->getCode(), $discountAmount);
            $total->addBaseTotalAmount($this->getCode(), $discountAmount);
        }

        return $this;
    }
}
