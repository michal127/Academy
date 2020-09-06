<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

/**
 * Class Collection
 * @package Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\Grid
 */
class Collection extends SearchResult
{
    protected $_eventPrefix = 'bulbulatory_recommendations_recommendation_grid_collection';

    protected $_eventObject = 'recommendation_grid_collection';

    /**
     * Extension of base _initSelect
     * Adding sender_email from joined customer_entity table for admin grid
     * Adding filters maps to columns
     * @return Collection|void
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['customer' => $this->getTable('customer_entity')],
            'main_table.customer_id = customer.entity_id',
            ['sender_email' => 'email']
        );

        $this->addFilterToMap('sender_email', 'customer.email');
        $this->addFilterToMap('email', 'main_table.email');
        $this->addFilterToMap('hash', 'main_table.hash');
        $this->addFilterToMap('status', 'main_table.status');
        $this->addFilterToMap('created_at', 'main_table.created_at');
        $this->addFilterToMap('confirmed_at', 'main_table.confirmed_at');
    }
}
