<?php
namespace Jnext\Megamenu\Model\Category\Attribute\Source;
class Blockoptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {

        $option[] = ['value' => 'no', 'label' => __('No')];
        $option[] = ['value' => 'only_static_block', 'label' => __('Yes, Only Static Block')];
        $option[] = ['value' => 'only_block_content', 'label' => __('Yes, Only Block Content')];

        return $option;
    }
}