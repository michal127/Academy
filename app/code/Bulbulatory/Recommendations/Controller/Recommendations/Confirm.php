<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;

use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Confirm
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Confirm extends Action\Action
{
    /**
     * @var RecommendationRepository
     */
    private $recommendationRepository;

    public function __construct(Context $context, RecommendationRepository $recommendationRepository)
    {
        $this->recommendationRepository = $recommendationRepository;
        parent::__construct($context);
    }

    const ROUTE = 'customer/recommendations/confirm';

    public function execute()
    {
        $hash = $this->getRequest()->getParam('hash');
        if (!empty($hash) && $this->recommendationRepository->confirmRecommendation(base64_decode($hash)) > 0) {
            $this->messageManager->addSuccessMessage(__('Thank you for visiting our shop!'));
        }

        $this->resultRedirectFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('/');
    }
}
