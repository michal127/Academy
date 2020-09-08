<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;

/**
 * Class Delete
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Listing
 */
class Delete extends AbstractDeleteAction
{
    public function execute()
    {
        $id = (int)$this->_request->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!empty($id) && $this->deleteRecommendationsRowsByIds([$id]) > 0) {
            $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
        } else {
            $this->messageManager->addErrorMessage(__('Invalid request - missing row ID'));
        }
        return $resultRedirect->setPath('bulbulatory_recommendations/listing/index');
    }
}
