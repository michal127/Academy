<?php


namespace Bulbulatory\Recommendations\Model;


use Bulbulatory\Recommendations\Api\Data\RecommendationRepositoryInterface;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class RecommendationRepository
 * @package Bulbulatory\Recommendations\Model
 */
class RecommendationRepository implements RecommendationRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * RecommendationRepository constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->connection = $resourceConnection->getConnection();
    }

    /**
     * Deleting recommendations rows by given IDs
     * @param int[] $ids Array of ids to delete
     * @return int Number of deleted rows
     */
    public function deleteByIds(array $ids)
    {
        return $this->connection->delete(Recommendation::MAIN_TABLE, 'id IN (' . implode(',', $ids) . ')');
    }
}
