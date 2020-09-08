<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;


use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class DeleteMass
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Listing
 */
class DeleteMass extends AbstractDeleteAction
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * DeleteMass constructor.
     * @param Action\Context $context
     * @param ResourceConnection $resource
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Action\Context $context, ResourceConnection $resource, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $resource);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $selectedRowsIds = $this->filter->getCollection($this->collectionFactory->create())->getAllIds();
            if (!empty($selectedRowsIds)) {
                $deletedRows = $this->deleteRecommendationsRowsByIds($selectedRowsIds);
                $this->messageManager->addSuccessMessage(__('Successfully deleted ' . $deletedRows . ' rows'));
            } else {
                $this->messageManager->addErrorMessage(__('Invalid request'));
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Invalid request'));
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/listing/index');
    }
}
