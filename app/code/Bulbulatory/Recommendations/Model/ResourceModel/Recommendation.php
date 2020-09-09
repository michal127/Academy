<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Recommendation
 * @package Bulbulatory\Recommendations\Model\ResourceModel
 */
class Recommendation extends AbstractDb
{
    public const MAIN_TABLE = 'bulbulatory_recommendations';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'id');
    }
}
