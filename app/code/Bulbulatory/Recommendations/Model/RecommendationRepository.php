<?php


namespace Bulbulatory\Recommendations\Model;


use Bulbulatory\Recommendations\Api\Data\RecommendationRepositoryInterface;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation as RecommendationResource;
use Exception;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Ramsey\Uuid\Uuid;

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
     * @var RecommendationFactory
     */
    private $recommendationFactory;
    /**
     * @var RecommendationResource
     */
    private $recommendationResource;

    /**
     * RecommendationRepository constructor.
     * @param ResourceConnection $resourceConnection
     * @param RecommendationFactory $recommendationFactory
     * @param RecommendationResource $recommendationResource
     */
    public function __construct(ResourceConnection $resourceConnection, RecommendationFactory $recommendationFactory, RecommendationResource $recommendationResource)
    {
        $this->recommendationFactory = $recommendationFactory;
        $this->recommendationResource = $recommendationResource;
        $this->connection = $resourceConnection->getConnection();
    }

    /**
     * Deleting recommendations rows by given IDs
     * @param int[] $ids Array of ids to delete
     * @return int Number of deleted rows
     */
    public function deleteByIds(array $ids)
    {
        return $this->connection->delete(RecommendationResource::MAIN_TABLE, 'id IN (' . implode(',', $ids) . ')');
    }

    /**
     * Checking if recommendation for given customer and recommended email already exist in database
     * @param Customer $customer
     * @param string $recommendedEmail
     * @return bool
     */
    public function isRecommendationExist(Customer $customer, string $recommendedEmail)
    {

        $select = $this->connection->select()
            ->from(RecommendationResource::MAIN_TABLE)
            ->columns('COUNT(*) as count')
            ->where('customer_id = ?', $customer->getId())
            ->where('email = ?', $recommendedEmail);

        $result = $this->connection->fetchRow($select->assemble());

        return (int)$result['count'] > 0;
    }

    /**
     * Create new recommendation record
     * @param Customer $customer
     * @param string $recommendedEmail
     * @return string created recommendation hash
     */
    public function createRecommendation(Customer $customer, string $recommendedEmail)
    {
        try {
            $hash = Uuid::uuid4();
            $recommendation = $this->recommendationFactory->create();
            $recommendation->setData([
                'email' => $recommendedEmail,
                'customer_id' => $customer->getId(),
                'hash' => $hash
            ]);
            $this->recommendationResource->save($recommendation);
            return $hash;
        } catch (Exception $e) {
            return '';
        }

    }

    /**
     * Confirming recommendation by given hash
     * Confirm only once if recommendation is still unconfirmed
     * @param string $hash
     * @return bool true - if confirmation was saved
     */
    public function confirmRecommendation(string $hash)
    {
        $updatedRows = $this->connection->update(
            RecommendationResource::MAIN_TABLE,
            ['status' => '1', 'confirmed_at' => date('Y-m-d H:i:s')],
            ['`hash` = ?' => $hash, '`status` <> ?' => 1]
        );
        return $updatedRows > 0;
    }
}
