<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Magento\Customer\Model\Session;
use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action\Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * Add constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Session $customerSession
     */
    public function __construct(Context $context, PageFactory $pageFactory, Session $customerSession)
    {
        $this->customerSession = $customerSession;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->customerSession->isLoggedIn()) {
            return $resultRedirect->setPath('/');
        }

        return $this->pageFactory->create();
    }


}
