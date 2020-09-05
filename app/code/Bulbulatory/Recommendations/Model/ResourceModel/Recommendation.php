<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Recommendation
 * @package Bulbulatory\Recommendations\Model\ResourceModel
 */
class Recommendation extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('bulbulatory_recommendations', 'id');
    }
}
