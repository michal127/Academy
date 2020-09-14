<?php


namespace Bulbulatory\Recommendations\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ConfigHelper
 * @package Bulbulatory\Recommendations\Helper
 */
class ConfigHelper extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ConfigHelper constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(Context $context, StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return bool enable configuration value of Bulbulatory_Recommendations module
     */
    public function isRecommendationsModuleEnabled()
    {
        try {
            return (bool)$this->scopeConfig->getValue(
                'recommendations/general/enable',
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getId()
            );

        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
