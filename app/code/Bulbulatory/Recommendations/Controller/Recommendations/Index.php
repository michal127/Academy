<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Bulbulatory\Recommendations\Helper\Config;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Index extends LoggedInAction
{
    const ROUTE = 'customer/recommendations/index';
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Add constructor.
     * @param Context $context
     * @param Config $configHelper
     * @param Session $customerSession
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        Config $configHelper,
        Session $customerSession,
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context, $configHelper, $customerSession);
    }

    /**
     * @return ResultInterface
     */
    protected function _execute(): ResultInterface
    {
        if ($this->isRecommendationModuleEnabled()) {
            return $this->pageFactory->create();
        }
        return $this->resultRedirectFactory->create()->setPath('/');
    }
}
