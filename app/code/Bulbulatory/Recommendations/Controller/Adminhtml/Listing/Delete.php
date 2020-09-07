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
        $id = (int)$this->_request->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!empty($id)) {
            try {
                $recommendationModel = $this->recommendationFactory->create();
                $recommendationModel->load($id);
                $recommendationModel->delete();
                $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage(__('An error occurred while deleting row'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Invalid request'));
        }
        return $resultRedirect->setPath('bulbulatory_recommendations/listing/index');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RECOMMENDATIONS_LISTING_DELETE_ACTION);
    }
}
