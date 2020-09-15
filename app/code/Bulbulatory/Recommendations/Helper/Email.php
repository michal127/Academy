<?php

namespace Bulbulatory\Recommendations\Helper;

use Bulbulatory\Recommendations\Controller\Recommendations\Confirm;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Email
 * @package Bulbulatory\Recommendations\Helper
 */
class Email extends AbstractHelper
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var string
     */
    protected $temp_id;
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        UrlInterface $urlBuilder
    )
    {
        parent::__construct($context);
        $this->urlBuilder = $urlBuilder;
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
    }


    /**
     * Return template id according to store
     *
     * @param $xmlPath
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getTemplateId(string $xmlPath)
    {
        return $this->scopeConfig->getValue(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getStoreId()
        );
    }

    /**
     * Generate email template
     * @param array $emailTemplateVariables
     * @param array $senderInfo
     * @param array $receiverInfo
     * @return Email
     * @throws NoSuchEntityException
     */
    public function generateTemplate(array $emailTemplateVariables, array $senderInfo, array $receiverInfo)
    {
        $this->_transportBuilder->setTemplateIdentifier($this->temp_id)
            ->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $this->_storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom($senderInfo)
            ->addTo($receiverInfo['email'], $receiverInfo['name']);
        return $this;
    }

    /**
     * Sending recommendation email
     * @param string $emailTemplateID
     * @param string $recommendedEmail
     * @param string $senderName
     * @param string $hash
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendRecommendationEmail(string $emailTemplateID, string $recommendedEmail, string $senderName, string $hash)
    {
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
            'senderName' => $senderName,
        ];

        $this->temp_id = $this->getTemplateId($emailTemplateID);
        $this->inlineTranslation->suspend();
        $this->generateTemplate($templateVars, $senderInfo, $receiverInfo);
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
