<?php


namespace Bulbulatory\Recommendations\Block;


use Bulbulatory\Recommendations\Helper\RecommendationsHelper;
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
     * @var RecommendationsHelper
     */
    private $recommendationsHelper;

    /**
     * AccountLink constructor.
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param RecommendationsHelper $recommendationsHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        RecommendationsHelper $recommendationsHelper,
        array $data = []
    )
    {
        $this->recommendationsHelper = $recommendationsHelper;
        parent::__construct($context, $defaultPath, $data);
    }

    /**
     * Rendering recommendation account link only when Bulbulatory_Recommendations module is enabled
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->recommendationsHelper->isRecommendationsModuleEnabled()) {
            return parent::_toHtml();
        }

        return '';
    }
}
