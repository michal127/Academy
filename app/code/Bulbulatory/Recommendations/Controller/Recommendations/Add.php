<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class Add
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Add extends LoggedInAction implements HttpPostActionInterface
{
    /**
     * @var RecommendationRepository
     */
    private $recommendationsRepository;

    public function __construct(Context $context, Session $customerSession, RecommendationRepository $recommendationRepository)
    {
        $this->recommendationsRepository = $recommendationRepository;
        parent::__construct($context, $customerSession);
    }

    /**
     * @return ResultInterface
     */
    protected function _execute(): ResultInterface
    {
        $customer = $this->customerSession->getCustomer();

        $recommendedEmail = $this->getRequest()->getParam('recoEmail');

        if (empty($recommendedEmail)) {
            $this->messageManager->addErrorMessage(__('Email field cannot be empty!'));
        } elseif ($recommendedEmail === $customer->getEmail()) {
            $this->messageManager->addErrorMessage(__('Recommended email cannot be same as yours!'));
        } elseif ($this->recommendationsRepository->isRecommendationExist($customer, $recommendedEmail)) {
            $this->messageManager->addErrorMessage(__('You already created recommendation for given email address!'));
        } else {
            $hash = Uuid::uuid4();
            if ($this->recommendationsRepository->createRecommendation($customer, $recommendedEmail, $hash)) {

                //TODO send email
                $this->messageManager->addSuccessMessage(__('Recommendation send successfully!'));
            }else{
                $this->messageManager->addErrorMessage(__('An error occurred while sending recommendation. please try again.'));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('customer/recommendations/index');
    }
}
