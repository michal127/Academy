<?php


namespace Bulbulatory\Recommendations\Helper;

use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Bulbulatory\Recommendations\Model\Source\Status;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\Customer;
use Magento\Framework\Phrase;

/**
 * Class Recommendation
 * Helper class for business logic related methods for recommendations module
 * @package Bulbulatory\Recommendations\Helper
 */
class Recommendation extends AbstractHelper
{

    const MAX_DISCOUNT_PERCENTAGE = 35;

    /**
     * @var RecommendationRepository
     */
    private $recommendationRepository;

    /**
     * Recommendation constructor.
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     */
    public function __construct(Context $context, RecommendationRepository $recommendationRepository)
    {
        parent::__construct($context);
        $this->recommendationRepository = $recommendationRepository;
    }

    /**
     * Returns integer value of customer discount by number of his confirmed recommendations
     * @param int $confirmedRecommendations
     * @return int
     */
    public function getDiscountValueForCustomer(int $confirmedRecommendations)
    {
        $discount = floor($confirmedRecommendations / 10) * 5;  //add 5% of discount per each 10 recommendations

        return ($discount <= self::MAX_DISCOUNT_PERCENTAGE) ? $discount : self::MAX_DISCOUNT_PERCENTAGE;
    }

    /**
     * Returns translated text of recommendation status by status_id
     * @param int $statusID
     * @return Phrase|string
     */
    public function getRecommendationStatusByID(int $statusID)
    {
        $statusName = '';
        switch ($statusID) {
            case 0:
                $statusName = __(Status::STATUS_UNCONFIRMED);
                break;
            case 1:
                $statusName = __(Status::STATUS_CONFIRMED);
                break;
        }

        return $statusName;
    }

    /**
     * Returns number of confirmed recommendations for given customer
     * @param Customer $customer
     * @return int
     */
    public function getConfirmedRecommendationsCountForCustomer(Customer $customer)
    {
        return $this->recommendationRepository->getConfirmedRecommendationsCountForCustomer($customer);
    }
}