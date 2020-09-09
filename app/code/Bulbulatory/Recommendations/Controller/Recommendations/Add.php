<?php


namespace Bulbulatory\Recommendations\Controller\Recommendations;


use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Add
 * @package Bulbulatory\Recommendations\Controller\Recommendations
 */
class Add extends LoggedInAction implements HttpPostActionInterface
{

    /**
     * @return ResultInterface
     */
    protected function _execute(): ResultInterface
    {

        $email = $this->getRequest()->getParam('recoEmail');

        if (!empty($email)) {
            /* TODO handle request*/
            $this->messageManager->addSuccessMessage('Recommendation send successfully!');
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('customer/recommendations/index');
    }
}
