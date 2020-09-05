<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel\Recommendation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Bulbulatory\Recommendations\Model\Recommendation as RecommendationModel;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation as RecommendationResource;

/**
 * Class Collection
 * @package Bulbulatory\Recommendations\Model\ResourceModel\Recommendation
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected $_eventPrefix = 'bulbulatory_recommendations_recommendation_collection';

    protected $_eventObject = 'recommendation_collection';

    protected function _construct()
    {
        $this->_init(RecommendationModel::class, RecommendationResource::class);
    }
}
