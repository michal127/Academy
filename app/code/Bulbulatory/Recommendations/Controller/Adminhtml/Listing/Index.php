<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * Displaying admin recommendations list
 */
class Index extends Action
{
    const RECOMMENDATIONS_LISTING_GRID_RESOURCE = 'Bulbulatory_Recommendations::recommendations_listing_grid';

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Action\Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Recommendations list')));

        return $resultPage;
    }

    /**
     * Blocking access to action by grid listing resource
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RECOMMENDATIONS_LISTING_GRID_RESOURCE);
    }
}
