<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Bulbulatory\Recommendations\Helper\Config;
use Bulbulatory\Recommendations\Helper\Email;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Exception;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;

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
     * @var Email
     */
    private $emailHelper;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Add constructor.
     * @param Context $context
     * @param Config $configHelper
     * @param Session $customerSession
     * @param Email $emailHelper
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Config $configHelper,
        Session $customerSession,
        Email $emailHelper,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->emailHelper = $emailHelper;
        $this->recommendationsRepository = $recommendationRepository;
        parent::__construct($context, $configHelper, $customerSession);
    }

    /**
     * @return ResultInterface
     */
    protected function _execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($this->isRecommendationModuleEnabled()) {
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
            return $resultRedirect->setPath(Index::ROUTE);
        }
        return $resultRedirect->setPath('/');
    }

    /**
     * Creating recommendation entry in database and sending recommendation email
     * @param Customer $customer
     * @param string $recommendedEmail
     * @return bool true if recommendation was saved and send successfully
     */
    private function createRecommendation(Customer $customer, string $recommendedEmail)
    {
        $hash = $this->recommendationsRepository->createRecommendation($customer, $recommendedEmail);
        if (!empty($hash)) {
            try {
                $this->emailHelper->sendRecommendationEmail(self::XML_PATH_EMAIL_TEMPLATE_FIELD, $recommendedEmail, $customer->getName(), $hash);
                return true;
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }
}
