<?php

namespace Thesis\QuickOrder\UI\Component\Listing\Column;

class ActionsOrderSelect implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
        ['value' => 1, 'label' => __('Pending')],
        ['value' => 2, 'label' => __('Close')],
        ['value' => 3, 'label' => __('Process')]
    ];
    }
}
