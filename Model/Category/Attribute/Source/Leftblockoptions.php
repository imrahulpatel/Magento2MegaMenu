<?php
namespace Jnext\Megamenu\Model\Category\Attribute\Source;
class Leftblockoptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {

        $option[] = ['value' => 'no', 'label' => __('No')];
        $option[] = ['value' => 'sub_menu', 'label' => __('Subcategories Menu')];
        $option[] = ['value' => 'sub_expanded', 'label' => __('Subcategories Expanded')];
        $option[] = ['value' => 'sub_expanded_with_image', 'label' => __('Subcategories Expanded with Image')];
        $option[] = ['value' => 'only_static_block', 'label' => __('Yes, Only Static Block')];
        $option[] = ['value' => 'only_block_content', 'label' => __('Yes, Only Block Content')];

        return $option;
    }
}