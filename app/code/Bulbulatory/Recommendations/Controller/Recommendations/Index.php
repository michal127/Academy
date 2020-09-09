<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


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
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Add constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Session $customerSession
     */
    public function __construct(Context $context, Session $customerSession, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context, $customerSession);
    }

    /**
     * @return ResultInterface
     */
    protected function _execute(): ResultInterface
    {
        return $this->pageFactory->create();
    }
}
