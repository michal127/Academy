<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;

use Bulbulatory\Recommendations\Helper\Config;
use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class AbstractRecommendationAction
 * Base abstract class form /customer/recommendations controller actions
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
abstract class AbstractRecommendationAction extends Action\Action
{
    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * AbstractRecommendationAction constructor.
     * @param Context $context
     * @param Config $configHelper
     */
    public function __construct(Context $context, Config $configHelper)
    {
        $this->configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface|ResponseInterface
     */
    abstract public function execute();

    /**
     * @return mixed
     */
    protected function isRecommendationModuleEnabled()
    {
        return $this->configHelper->isRecommendationsModuleEnabled();
    }
}
