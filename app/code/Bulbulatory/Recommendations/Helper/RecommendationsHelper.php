<?php

namespace Bulbulatory\Recommendations\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RecommendationsHelper
 * @package Bulbulatory\Recommendations\Helper
 */
class RecommendationsHelper extends AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

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
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder
    )
    {
        $this->_scopeConfig = $context;
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
    }

    /**
     * Return store configuration
     *
     * @param string $path
     * @param int $storeId
     * @return mixed
     */
    public function getConfigValue(string $path, int $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Return store
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
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
        return $this->getConfigValue($xmlPath, $this->getStore()->getStoreId());
    }

    /**
     * Generate email template
     * @param array $emailTemplateVariables
     * @param array $senderInfo
     * @param array $receiverInfo
     * @return RecommendationsHelper
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
     * @param array $emailTemplateVariables
     * @param array $senderInfo
     * @param array $receiverInfo
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendRecommendationEmail(string $emailTemplateID, array $emailTemplateVariables, array $senderInfo, array $receiverInfo)
    {
        $this->temp_id = $this->getTemplateId($emailTemplateID);
        $this->inlineTranslation->suspend();
        $this->generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo);
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * @return bool enable configuration value of Bulbulatory_Recommendations module
     */
    public function isRecommendationsModuleEnabled()
    {
        try {
            return (bool)$this->getConfigValue('recommendations/general/enable', $this->getStore()->getStoreId());
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
