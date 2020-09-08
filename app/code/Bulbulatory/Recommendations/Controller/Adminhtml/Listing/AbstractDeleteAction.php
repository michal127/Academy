<?php


namespace Bulbulatory\Recommendations\Controller\Adminhtml\Listing;

use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Abstract class for admin listing delete actions
 * Class AbstractDeleteAction
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Listing
 */
abstract class AbstractDeleteAction extends Action
{
    const RECOMMENDATIONS_LISTING_DELETE_ACTION = 'Bulbulatory_Recommendations::recommendations_listing_delete';

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * AbstractDeleteAction constructor.
     * @param Action\Context $context
     * @param ResourceConnection $resource
     */
    public function __construct(Action\Context $context, ResourceConnection $resource)
    {
        $this->connection = $resource->getConnection();
        parent::__construct($context);
    }

    /**
     * @param array $ids Array of ids to delete
     * @return int Number of deleted rows
     */
    protected function deleteRecommendationsRowsByIds(array $ids)
    {
        return $this->connection->delete(Recommendation::MAIN_TABLE, 'id IN (' . implode(',', $ids) . ')');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RECOMMENDATIONS_LISTING_DELETE_ACTION);
    }
}
