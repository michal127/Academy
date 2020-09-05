<?php

namespace Bulbulatory\Recommendations\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package Bulbulatory\Recommendations\Model\Source
 */
class Status implements ArrayInterface
{

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Unconfirmed')],
            ['value' => 1, 'label' => __('Confirmed')]
        ];
    }
}
