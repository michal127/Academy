<?php


namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;

use Bulbulatory\Recommendations\Model\RecommendationFactory;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Listing
 */
class Delete extends Action
{
    const RECOMMENDATIONS_LISTING_DELETE_ACTION = 'Bulbulatory_Recommendations::recommendations_listing_delete';

    /**
     * @var RecommendationFactory
     */
    private $recommendationFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param RecommendationFactory $recommendationFactory
     */
    public function __construct(Action\Context $context, RecommendationFactory $recommendationFactory)
    {
        parent::__construct($context);
        $this->recommendationFactory = $recommendationFactory;
    }

    public function execute()
    {
        //TODO finish delete action
        $id = (int)$this->_request->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $recoModel = $this->recommendationFactory->create();
        try {
            $recoModel->load($id);
            $recoModel->delete();
            $this->messageManager->addSuccessMessage(__('Recommendation record deleted!'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('An erroor occured while deleting row'));
        }
       return $resultRedirect->setPath('bulbulatory_recommendations/listing/delete');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RECOMMENDATIONS_LISTING_DELETE_ACTION);
    }
}
