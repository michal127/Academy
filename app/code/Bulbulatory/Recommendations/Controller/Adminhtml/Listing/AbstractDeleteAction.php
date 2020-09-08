<?php


namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;

use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Magento\Backend\App\Action;

/**
 * Abstract class for admin listing delete actions
 * Class AbstractDeleteAction
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Listing
 */
abstract class AbstractDeleteAction extends Action
{
    const RECOMMENDATIONS_LISTING_DELETE_ACTION = 'Bulbulatory_Recommendations::recommendations_listing_delete';

    /**
     * @var RecommendationRepository
     */
    private $recommendationsRepository;

    /**
     * AbstractDeleteAction constructor.
     * @param Action\Context $context
     * @param RecommendationRepository $recommendationRepository
     */
    public function __construct(Action\Context $context, RecommendationRepository $recommendationRepository)
    {
        $this->recommendationsRepository = $recommendationRepository;
        parent::__construct($context);
    }

    /**
     * @param array $ids Array of ids to delete
     * @return int Number of deleted rows
     */
    protected function deleteRecommendationsRowsByIds(array $ids)
    {
        return $this->recommendationsRepository->deleteByIds($ids);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RECOMMENDATIONS_LISTING_DELETE_ACTION);
    }
}
