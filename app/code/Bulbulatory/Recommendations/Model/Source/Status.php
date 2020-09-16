<?php

namespace Bulbulatory\Recommendations\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package Bulbulatory\Recommendations\Model\Source
 */
class Status implements ArrayInterface
{
    const STATUS_UNCONFIRMED_ENG = 'Unconfirmed';
    const STATUS_CONFIRMED_ENG = 'Confirmed';

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __(self::STATUS_UNCONFIRMED_ENG)],
            ['value' => 1, 'label' => __(self::STATUS_CONFIRMED_ENG)]
        ];
    }
}
