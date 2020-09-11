<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;

use Magento\Framework\App\Action;

/**
 * Class Confirm
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Confirm extends Action\Action
{
    const ROUTE = 'customer/recommendations/confirm';

    public function execute()
    {

        $hash = $this->getRequest()->getParam('hash');
        if (!empty($hash)) {
            //TODO save confirmation
            $this->messageManager->addSuccessMessage(__('Thank you for visiting our shop!'));
        }

        $this->resultRedirectFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('/');
    }
}
