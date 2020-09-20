<?php


namespace Bulbulatory\Recommendations\Model\Total\Quote;


use Bulbulatory\Recommendations\Helper\Config;
use Bulbulatory\Recommendations\Helper\Recommendation as RecommendationHelper;
use Magento\Framework\Phrase;
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
     * @var int
     */
    private $recommendationDiscount = 0;
    /**
     * @var Phrase
     */
    private $discountLabel = '';

    /**
     * RecommendationDiscount constructor.
     * @param Session $session
     * @param RecommendationHelper $recommendationHelper
     * @param Config $config
     */
    public function __construct(Session $session, RecommendationHelper $recommendationHelper, Config $config)
    {
        if ($config->isRecommendationsModuleEnabled()) {
            $confirmedRecommendations = $recommendationHelper->getConfirmedRecommendationsCountForCustomer($session->getCustomer());
            $this->recommendationDiscount = $recommendationHelper->getDiscountValueForCustomer($confirmedRecommendations);
            $this->discountLabel = __('Recommendations discount -%1%', $this->recommendationDiscount);
        }
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

        if ($this->recommendationDiscount <= 0) {
            return $this;
        }


        $discountAmount = $this->getRecommendationDiscountAmount($total);
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

        if ($this->recommendationDiscount > 0) {
            $result = [
                'code' => $this->getCode(),
                'title' => $this->discountLabel,
                'value' => $this->getRecommendationDiscountAmount($total)
            ];
        }
        return $result;
    }

    /**
     * @param Total $total
     * @return string
     */
    private function getRecommendationDiscountAmount(Total $total)
    {
        return "-" . ($total->getSubtotal() * $this->recommendationDiscount / 100);
    }

}
