<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;


/**
 * Class DeleteMass
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Listing
 */
class DeleteMass extends AbstractDeleteAction
{

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $selectedRowsIds = $this->_request->getParam('selected');
        if (!empty($selectedRowsIds)) {
            $deletedRows = $this->deleteRecommendationsRowsByIds($selectedRowsIds);
            $this->messageManager->addSuccessMessage(__('Successfully deleted ' . $deletedRows . ' rows'));
        } else {
            $this->messageManager->addErrorMessage(__('Invalid request'));
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/listing/index');
    }
}
