<?php


namespace Bulbulatory\Recommendations\Block;


use Bulbulatory\Recommendations\Helper\ConfigHelper;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\View\Element\Html\Link\Current;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class AccountLink
 * @package Bulbulatory\Recommendations\Block
 */
class AccountLink extends Current
{
    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * AccountLink constructor.
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param ConfigHelper $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        ConfigHelper $configHelper,
        array $data = []
    )
    {
        $this->configHelper = $configHelper;
        parent::__construct($context, $defaultPath, $data);
    }

    /**
     * Rendering recommendation account link only when Bulbulatory_Recommendations module is enabled
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->configHelper->isRecommendationsModuleEnabled()) {
            return parent::_toHtml();
        }

        return '';
    }
}
