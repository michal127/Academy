<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Bulbulatory\Recommendations\Helper\RecommendationsHelper;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Exception;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\UrlInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class Add
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Add extends LoggedInAction implements HttpPostActionInterface
{
    const ROUTE = 'customer/recommendations/add';
    const POST_PARAM_EMAIL = 'recommendedEmail';
    const XML_PATH_EMAIL_TEMPLATE_FIELD = 'recommendations/general/recommendation_email';

    /**
     * @var RecommendationRepository
     */
    private $recommendationsRepository;
    /**
     * @var RecommendationsHelper
     */
    private $recommendationsHelper;
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(Context $context, Session $customerSession, RecommendationRepository $recommendationRepository, RecommendationsHelper $recommendationsHelper, UrlInterface $urlBuilder)
    {
        $this->recommendationsHelper = $recommendationsHelper;
        $this->urlBuilder = $urlBuilder;
        $this->recommendationsRepository = $recommendationRepository;
        parent::__construct($context, $customerSession);
    }

    /**
     * @return ResultInterface
     */
    protected function _execute(): ResultInterface
    {
        $customer = $this->customerSession->getCustomer();

        $recommendedEmail = $this->getRequest()->getParam(self::POST_PARAM_EMAIL);

        if (empty($recommendedEmail)) {
            $this->messageManager->addErrorMessage(__('Email field cannot be empty!'));
        } elseif ($recommendedEmail === $customer->getEmail()) {
            $this->messageManager->addErrorMessage(__('Recommended email cannot be same as yours!'));
        } elseif ($this->recommendationsRepository->isRecommendationExist($customer, $recommendedEmail)) {
            $this->messageManager->addErrorMessage(__('You already created recommendation for given email address!'));
        } elseif (!$this->createRecommendation($customer, $recommendedEmail)) {
            $this->messageManager->addErrorMessage(__('An error occurred while sending recommendation. please try again.'));
        } else {
            $this->messageManager->addSuccessMessage(__('Recommendation send successfully!'));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath(Index::ROUTE);
    }

    /**
     * Creating recommendation entry in database and sending recommendation email
     * @param Customer $customer
     * @param string $recommendedEmail
     * @return bool true if recommendation was saved and send successfully
     */
    private function createRecommendation(Customer $customer, string $recommendedEmail)
    {
        try {
            $hash = Uuid::uuid4();
        } catch (Exception $e) {
            return false;
        }
        if ($this->recommendationsRepository->createRecommendation($customer, $recommendedEmail, $hash)) {
            try {
                $receiverInfo = [
                    'name' => $recommendedEmail,
                    'email' => $recommendedEmail
                ];

                $senderInfo = [
                    'name' => 'Bulbulatory',
                    'email' => 'bulbulatory@magento.pl',
                ];

                $templateVars = [
                    'recommendedEmail' => $recommendedEmail,
                    'confirmationUrl' => $this->urlBuilder->getUrl(Confirm::ROUTE, ['hash' => base64_encode($hash)]),
                    'senderName' => $customer->getName(),
                ];

                $this->recommendationsHelper->sendRecommendationEmail(self::XML_PATH_EMAIL_TEMPLATE_FIELD, $templateVars, $senderInfo, $receiverInfo);
                return true;
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}
