<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class LoggedInAction
 * Abstract Action class for logged in customers only
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
abstract class LoggedInAction extends Action\Action
{
    private $customerSession;

    /**
     * LoggedInAction constructor.
     * @param Context $context
     * @param Session $customerSession
     */
    public function __construct(Context $context, Session $customerSession)
    {
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Blocking access for not logged in customers
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->customerSession->isLoggedIn()) {
            return $resultRedirect->setPath('/');
        }
        return $this->_execute();
    }

    /**
     * Implementation of execute() method
     * @return ResultInterface
     */
    abstract protected function _execute(): ResultInterface;
}
