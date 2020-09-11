<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Bulbulatory\Recommendations\Helper\Email;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Exception;
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
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(Context $context, Session $customerSession, RecommendationRepository $recommendationRepository, Email $emailHelper, UrlInterface $urlBuilder)
    {
        $this->emailHelper = $emailHelper;
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
                        'confirmationUrl' => $this->urlBuilder->getUrl('recommendations/action/confirm', ['hash' => base64_encode($hash)]), //TODO add valid link
                        'senderName' => $customer->getName(),
                    ];

                    $this->emailHelper->sendRecommendationEmail(self::XML_PATH_EMAIL_TEMPLATE_FIELD,$templateVars, $senderInfo, $receiverInfo);
                    $this->messageManager->addSuccessMessage(__('Recommendation send successfully!'));
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage(__('An error occurred while sending recommendation. please try again.'));
                }
            } else {
                $this->messageManager->addErrorMessage(__('An error occurred while sending recommendation. please try again.'));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('customer/recommendations/index');
    }
}
