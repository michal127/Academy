<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Add extends Action\Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Add constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        //Todo prepare saving recommendation and sending mail, prepare layout
        return $this->pageFactory->create();
    }
}
