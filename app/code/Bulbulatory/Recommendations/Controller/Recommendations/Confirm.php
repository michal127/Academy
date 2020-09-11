<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;

use Bulbulatory\Recommendations\Helper\RecommendationsHelper;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Magento\Framework\App\Action\Context;

/**
 * Class Confirm
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Confirm extends AbstractRecommendationAction
{
    const ROUTE = 'customer/recommendations/confirm';

    /**
     * @var RecommendationRepository
     */
    private $recommendationRepository;

    /**
     * Confirm constructor.
     * @param Context $context
     * @param RecommendationsHelper $recommendationsHelper
     * @param RecommendationRepository $recommendationRepository
     */
    public function __construct(
        Context $context,
        RecommendationsHelper $recommendationsHelper,
        RecommendationRepository $recommendationRepository
    )
    {
        $this->recommendationRepository = $recommendationRepository;
        parent::__construct($context, $recommendationsHelper);
    }

    public function execute()
    {
        if ($this->isRecommendationModuleEnabled()) {
            $hash = $this->getRequest()->getParam('hash');
            if (!empty($hash) && $this->recommendationRepository->confirmRecommendation(base64_decode($hash))) {
                $this->messageManager->addSuccessMessage(__('Thank you for visiting our shop!'));
            }
        }

        return $this->resultRedirectFactory->create()->setPath('/');
    }
}
