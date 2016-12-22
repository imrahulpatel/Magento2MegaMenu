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