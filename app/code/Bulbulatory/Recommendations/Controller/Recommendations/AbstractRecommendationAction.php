<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;

use Bulbulatory\Recommendations\Helper\RecommendationsHelper;
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
     * @var RecommendationsHelper
     */
    protected $recommendationsHelper;

    /**
     * AbstractRecommendationAction constructor.
     * @param Context $context
     * @param RecommendationsHelper $recommendationsHelper
     */
    public function __construct(Context $context, RecommendationsHelper $recommendationsHelper)
    {
        $this->recommendationsHelper = $recommendationsHelper;
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
        return $this->recommendationsHelper->isRecommendationsModuleEnabled();
    }
}
