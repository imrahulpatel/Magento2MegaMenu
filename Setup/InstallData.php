<?php
namespace Jnext\Megamenu\Setup;

use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

class InstallData implements InstallDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
        $megaAttributeGroup = $categorySetup->addAttributeGroup($entityTypeId, $attributeSetId, 'Megamenu','1000');
      
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_menu_type', [
                'type' => 'varchar',
                'label' => 'Menu Type',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Menutypes',
                'required' => false,
                'sort_order' => 1,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_menu_width', [
                'type' => 'varchar',
                'label' => 'Menu Width',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Blockwidth',
                'required' => false,
                'sort_order' => 2,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_category_label', [
                'type' => 'varchar',
                'label' => 'Category Label',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Categorylabels',
                'required' => false,
                'sort_order' => 3,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_category_thumbnail_image', [
                'type' => 'varchar',
                'label' => 'Category Thumbnail Image',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 4,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        //-----------------------------------------------TOP
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_display_top_block', [
                'type' => 'varchar',
                'label' => 'Display Top Block?',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Blockoptions',
                'required' => false,
                'sort_order' => 5,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_top_block_id', [
                'type' => 'int',
                'label' => 'Selct Top Static Block',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'required' => false,
                'sort_order' => 6,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_top_block_content', [
                'type' => 'text',
                'label' => 'Top Block Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 7,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu',
            ]
        );
        //-----------------------------------------------LEFT
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_display_left_block', [
                'type' => 'varchar',
                'label' => 'Display Left Block?',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Leftblockoptions',
                'required' => false,
                'sort_order' => 8,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_left_block_id', [
                'type' => 'int',
                'label' => 'Selct Left Static Block',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'required' => false,
                'sort_order' => 9,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_left_block_content', [
                'type' => 'text',
                'label' => 'Left Block Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_left_block_width', [
                'type' => 'varchar',
                'label' => 'Left Block Width',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Blockwidth',
                'required' => false,
                'sort_order' => 11,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        //-----------------------------------------------RIGHT
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_display_right_block', [
                'type' => 'varchar',
                'label' => 'Display Right Block?',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Blockoptions',
                'required' => false,
                'sort_order' => 12,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_right_block_id', [
                'type' => 'int',
                'label' => 'Selct Right Static Block',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'required' => false,
                'sort_order' => 13,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_right_block_content', [
                'type' => 'text',
                'label' => 'Right Block Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 14,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_right_block_width', [
                'type' => 'varchar',
                'label' => 'Right Block Width',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Blockwidth',
                'required' => false,
                'sort_order' => 15,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        //-----------------------------------------------BOTTOM
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_display_bottom_block', [
                'type' => 'varchar',
                'label' => 'Display Bottom Block?',
                'input' => 'select',
                'source' => 'Jnext\Megamenu\Model\Category\Attribute\Source\Blockoptions',
                'required' => false,
                'sort_order' => 16,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_bottom_block_id', [
                'type' => 'int',
                'label' => 'Selct Bottom Static Block',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'required' => false,
                'sort_order' => 17,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Megamenu',
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'mega_bottom_block_content', [
                'type' => 'text',
                'label' => 'Bottom Block Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 18,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Megamenu',
            ]
        );
    
        $id =  $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Megamenu');

        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_menu_type',30);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_static_width',31);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_category_label',32);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_category_thumbnail_image',33);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_display_top_block',34);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_top_block_id',35);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_top_block_content',36);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_display_left_block',37);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_left_block_id',38);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_left_block_content',39);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_left_block_width',40);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_display_right_block',41);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_right_block_id',42);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_right_block_content',43);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_right_block_width',44);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_display_bottom_block',45);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_bottom_block_id',46);
        $categorySetup->addAttributeToGroup($entityTypeId,$attributeSetId,$id,'mega_bottom_block_content',47);

        $installer->endSetup();
    }
}