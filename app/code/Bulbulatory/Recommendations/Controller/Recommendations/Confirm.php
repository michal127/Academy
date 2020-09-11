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
    const ROUTE = 'customer/recommendations/confirm';

    /**
     * @var RecommendationRepository
     */
    private $recommendationRepository;

    public function __construct(Context $context, RecommendationRepository $recommendationRepository)
    {
        $this->recommendationRepository = $recommendationRepository;
        parent::__construct($context);
    }


    public function execute()
    {
        $hash = $this->getRequest()->getParam('hash');
        if (!empty($hash) && $this->recommendationRepository->confirmRecommendation(base64_decode($hash))) {
            $this->messageManager->addSuccessMessage(__('Thank you for visiting our shop!'));
        }

        $this->resultRedirectFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('/');
    }
}
