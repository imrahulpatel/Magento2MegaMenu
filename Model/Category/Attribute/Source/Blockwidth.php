<?php
namespace Jnext\Megamenu\Model\Category\Attribute\Source;
class Blockwidth extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {

        $option[] = ['value' => 'width_standard', 'label' => __('Standard')];
        $option[] = ['value' => 'width_20', 'label' => __('20%')];
        $option[] = ['value' => 'width_30', 'label' => __('30%')];
        $option[] = ['value' => 'width_40', 'label' => __('40%')];
        $option[] = ['value' => 'width_50', 'label' => __('50%')];
        $option[] = ['value' => 'width_60', 'label' => __('60%')];
        $option[] = ['value' => 'width_70', 'label' => __('70%')];
        $option[] = ['value' => 'width_80', 'label' => __('80%')];
        $option[] = ['value' => 'width_90', 'label' => __('90%')];
        $option[] = ['value' => 'width_100', 'label' => __('100%')];

        return $option;
    }
}