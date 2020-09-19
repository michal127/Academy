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
        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        if (!$this->config->isRecommendationsModuleEnabled()) {
            return $this;
        }

        $confirmedRecommendations = $this->recommendationHelper->getConfirmedRecommendationsCountForCustomer($this->customer);
        $recommendationDiscount = $this->recommendationHelper->getDiscountValueForCustomer($confirmedRecommendations);

        if ($recommendationDiscount <= 0) {
            return $this;
        }

        $discountLabel = __('Recommendations discount -%1%', $recommendationDiscount);
        $totalAmount = $total->getSubtotal();

        $discountAmount = "-" . ($totalAmount * $recommendationDiscount / 100);

        $total->setRecommendationsDiscountDescription($discountLabel);
        $total->setRecommendationsDiscountAmount($discountAmount);


        $total->addTotalAmount($this->getCode(), $discountAmount);
        $total->addBaseTotalAmount($this->getCode(), $discountAmount);


        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array|null
     */
    public function fetch(Quote $quote, Total $total)
    {
        $result = null;
        $amount = $total->getRecommendationsDiscountAmount();

        if ($amount != 0) {
            $description = $total->getRecommendationsDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
                'value' => $amount
            ];
        }
        return $result;
    }

}
