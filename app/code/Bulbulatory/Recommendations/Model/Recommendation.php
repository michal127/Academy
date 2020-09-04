<?php

namespace Bulbulatory\Recommendations\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Recommendation
 * @package Bulbulatory\Recommendations\Model
 */
class Recommendation extends AbstractModel implements IdentityInterface
{
    protected $_cacheTag = 'bulbulatory_recommendations_recommendation';
    protected $_eventPrefix = 'bulbulatory_recommendations_recommendation';


    protected function _construct()
    {
        $this->_init('Bulbulatory\Recommendations\Model\ResourceModel\Recommendation');
    }

    public function getIdentities()
    {
        return [$this->_cacheTag . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        return [];
    }
}
