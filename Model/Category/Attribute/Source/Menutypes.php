<?php
namespace Jnext\Megamenu\Model\Category\Attribute\Source;
class Menutypes extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {

        $option[] = ['value' => 'default', 'label' => __('Default')];
        $option[] = ['value' => 'mega', 'label' => __('Megamenu')];

        return $option;
    }
}