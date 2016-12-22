<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Jnext\Megamenu\Model\Category\Attribute\Source;

/**
 * Catalog product landing page attribute source
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
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