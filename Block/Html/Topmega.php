<?php
namespace Jnext\Megamenu\Block\Html;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Theme\Block\Html\Topmenu;
use Magento\Cms\Model\BlockRepository;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;

/**
 * Html page top menu block
 */
class Topmega extends Topmenu
{
    protected $objectManager;
    protected $menuType;
    protected $extraClass;
    protected $category;
    protected $parentId;
    protected $categoryHelper;
    protected $storeManager;
    protected $currentStore;
    protected $mediaUrl;
    protected $categoryMediaUrl;

    /**
     * Cache identities
     *
     * @var array
     */
    protected $identities = [];

    /**
     * Top menu data tree
     *
     * @var \Magento\Framework\Data\Tree\Node
     */
    protected $_menu;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $registry;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Block factory
     *
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;
    /**
     * @var \Jnext\CategoriesUrl\Helper\Data
     */
    protected $dataHelper;
    /**
     * @param Template\Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        CategoryFactory $categoryFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        Registry $registry,
        \Jnext\Megamenu\Helper\Data $dataHelper,
        \Magento\Catalog\Helper\Output $categoryHelper,
        array $data = []
    ) {
        parent::__construct($context,$nodeFactory,$treeFactory, $data);
        $this->categoryFactory = $categoryFactory;
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
        $this->coreRegistry = $registry;
        $this->dataHelper = $dataHelper;
        $this->_menu = $nodeFactory->create(
            [
                'data' => [],
                'idField' => 'root',
                'tree' => $treeFactory->create()
            ]
        );
        $this->categoryHelper = $categoryHelper;
        $this->extraClass = '';
        $this->menuType = 'default';
        $this->storeManager = $storeManager;
        $this->currentStore = $this->storeManager->getStore();
        $this->mediaUrl = $this->currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $this->categoryMediaUrl = $this->mediaUrl."catalog/category/";
        $this->parentId = false;
    }
    
    /**
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function getHtml2($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            ['menu' => $this->_menu, 'block' => $this]
        );

        $this->_menu->setOutermostClass($outermostClass);
        $this->_menu->setChildrenWrapClass($childrenWrapClass);

        $html = $this->_getHtml2($this->_menu, $childrenWrapClass, $limit);

        $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->_menu, 'transportObject' => $transportObject]
        );
        $html = $transportObject->getHtml();

        return $html;
    }

    /**
     * Count All Subnavigation Items
     *
     * @param \Magento\Backend\Model\Menu $items
     * @return int
     */
    protected function _countItems($items)
    {
        $total = $items->count();
        foreach ($items as $item) {
            /** @var $item \Magento\Backend\Model\Menu\Item */
            if ($item->hasChildren()) {
                $total += $this->_countItems($item->getChildren());
            }
        }
        return $total;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu2($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';

        /*if (!$child->hasChildren()) {
            return $html;
        }*/

        $colStops = null;
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }
        
        $callParentSubmenu = true;
        $menuWidth = ($this->category->getMegaMenuWidth()) ? $this->category->getMegaMenuWidth() : '';
        $html .= '<div class="megamenu-wrapper '.$menuWidth.'">';
        if($this->category->getMegaDisplayTopBlock()!='no' && $this->category->getMegaDisplayTopBlock()!=null)
        {
            $option = $this->category->getMegaDisplayTopBlock() ? $this->category->getMegaDisplayTopBlock() : '';
            $width = $this->category->getMegaTopBlockWidth() ? $this->category->getMegaTopBlockWidth() : '';
            if($option=='only_static_block' && $this->category->getMegaTopBlockId()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="topblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->getBlockHtml($this->category->getMegaTopBlockId());
                $html .= '</div>';                
            }
            elseif($option=='only_block_content' && $this->category->getMegaTopBlockContent()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="topblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->categoryHelper->categoryAttribute($this->category, $this->category->getMegaTopBlockContent(), 'mega_top_block_content');
                $html .= '</div>';
            }
        }
        if($this->category->getMegaDisplayLeftBlock()!='no' && $this->category->getMegaDisplayLeftBlock()!=null)
        {
            $option = $this->category->getMegaDisplayLeftBlock() ? $this->category->getMegaDisplayLeftBlock() : '';
            $width = $this->category->getMegaLeftBlockWidth() ? $this->category->getMegaLeftBlockWidth() : '';
            if($option=='sub_menu' || $option=='sub_expanded' || $option=='sub_expanded_with_image')
            {
                if($option=='sub_expanded_with_image')
                    $option .= ' sub_expanded';
                $callParentSubmenu = false;
                $html .= '<div class="leftblock megamenu-block'.' '.$option.' '.$width.'">';
                if($child->hasChildren())
                {
                    $html .= '<ul class="level' . $childLevel . ' submenu">';
                        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
                    $html .= '</ul>';
                }
                else
                    $html .= '<div class="message info empty"><div>We can\'t find categories matching the selection.</div></div>';
                $html .= '</div>';
            }
            elseif($option=='only_static_block' && $this->category->getMegaLeftBlockId()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="leftblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->getBlockHtml($this->category->getMegaLeftBlockId());
                $html .= '</div>';
            }
            elseif($option=='only_block_content' && $this->category->getMegaLeftBlockContent()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="leftblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->categoryHelper->categoryAttribute($this->category, $this->category->getMegaLeftBlockContent(), 'mega_left_block_content');
                $html .= '</div>';
            }
        }
        if($this->category->getMegaDisplayRightBlock()!='no' && $this->category->getMegaDisplayRightBlock()!=null)
        {
            $option = $this->category->getMegaDisplayRightBlock() ? $this->category->getMegaDisplayRightBlock() : '';
            $width = $this->category->getMegaRightBlockWidth() ? $this->category->getMegaRightBlockWidth() : '';
            if($option=='only_static_block' && $this->category->getMegaRightBlockId()!='')
            {
                $callParentSubmenu = false;                
                $html .= '<div class="rightblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->getBlockHtml($this->category->getMegaRightBlockId());
                $html .= '</div>';
            }
            elseif($option=='only_block_content' && $this->category->getMegaRightBlockContent()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="rightblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->categoryHelper->categoryAttribute($this->category, $this->category->getMegaRightBlockContent(), 'mega_right_block_content');
                $html .= '</div>';
            }
        }
        if($this->category->getMegaDisplayBottomBlock()!='no' && $this->category->getMegaDisplayBottomBlock()!=null)
        {
            $option = $this->category->getMegaDisplayBottomBlock() ? $this->category->getMegaDisplayBottomBlock() : '';
            $width = $this->category->getMegaBottomBlockWidth() ? $this->category->getMegaBottomBlockWidth() : '';
            if($option=='only_static_block' && $this->category->getMegaBottomBlockId()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="bottomblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->getBlockHtml($this->category->getMegaBottomBlockId());
                $html .= '</div>';
            }
            elseif($option=='only_block_content' && $this->category->getMegaBottomBlockContent()!='')
            {
                $callParentSubmenu = false;
                $html .= '<div class="bottomblock megamenu-block'.' '.$option.' '.$width.'">';
                $html .= $this->categoryHelper->categoryAttribute($this->category, $this->category->getMegaBottomBlockContent(), 'mega_bottom_block_content');
                $html .= '</div>';                
            }
        }
        $html .= '</div>';
        if($callParentSubmenu)
            return $this->_addSubMenu($child, $childLevel, $childrenWrapClass, $limit);

        return $html;
    }

    protected function _getHtml(\Magento\Framework\Data\Tree\Node $menuTree,$childrenWrapClass,$limit,$colBrakes = [])
    {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            list($first,$second,$id) = explode('-', $child->getId());
            $child['class'] .= ' category_id_'.$id;

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                $child->getName()
            ) . '</span>';
            
            if($parentLevel==0 && $menuTree->getId())
            {
                $this->parentId = substr($menuTree->getId(), strrpos($menuTree->getId(), '-') + 1);
            }
            if($this->parentId && $this->category && $this->category->getMegaDisplayLeftBlock()!='no' && $this->category->getMegaDisplayLeftBlock()!=null && $this->category->getMegaDisplayLeftBlock()=='sub_expanded_with_image')
            {
                list($first,$second,$id) = explode('-', $child->getId());
                $cat = $this->categoryFactory->create();
                if($cat->load($id))
                {
                    if($cat->getMegaCategoryThumbnailImage() && $child->getLevel()>0)
                        $html .= '<img src="'.$this->categoryMediaUrl.$cat->getMegaCategoryThumbnailImage().'" alt="'.$child->getName().'" />';
                }
            }

            $html .= '</a>' . $this->_addSubMenu(
                $child,
                $childLevel,
                $childrenWrapClass,
                $limit
            ) . '</li>';
            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    protected function _getHtml2(\Magento\Framework\Data\Tree\Node $menuTree,$childrenWrapClass,$limit,$colBrakes = [])
    {
        $data_mage_init = 'data-mage-init=\'{"menu":{"responsive":true, "expanded":true, "position":{"my":"left top","at":"left bottom"}}}\'';
        $html = '<ul '.$data_mage_init.'>';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            $catLabelHtml = '';
            $this->category = null;
            if ($childLevel == 0)
            {
                $arrayId = explode('-',$child->_getData('id'));
                if(isset($arrayId[2]))
                {
                    $id = $arrayId[2];
                    $this->category = $this->categoryFactory->create();
                    $this->category->load($id);
                    $this->coreRegistry->unregister('current_categry_top_level');
                    $this->coreRegistry->register('current_categry_top_level',$this->category);

                    $this->menuType = ($this->category->getMegaMenuType()) ? $this->category->getMegaMenuType() : 'default';
                    
                    if($this->menuType=='mega')
                    {
                        if($catLabel=$this->category->getMegaCategoryLabel())
                        {
                            $this->extraClass .= ' '.strtolower($catLabel);
                            $catLabelHtml = '<span class="cat-label">'.$this->escapeHtml($this->dataHelper->getCatLabel($catLabel)).'</span>';
                        }
                        $this->extraClass .= ' '.$this->menuType;
                    }
                }
            }

            if($this->menuType=='mega')
            {
                $html .= '<li ' . $this->_getRenderedMenuItemAttributes2($child) . '>';
                $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                    $child->getName()
                ) .$catLabelHtml. '</span></a>' . $this->_addSubMenu2(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';
            }
            else
            {
                $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
                $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                    $child->getName()
                ) .$catLabelHtml. '</span></a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';
            }
            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Generates string with all attributes that should be present in menu item element
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return string
     */
    protected function _getRenderedMenuItemAttributes2(\Magento\Framework\Data\Tree\Node $item)
    {
        $html = '';
        $attributes = $this->_getMenuItemAttributes($item);
        foreach ($attributes as $attributeName => $attributeValue) {
            $html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) .$this->extraClass. '"';
        }
        return $html;
    }

    /**
     * Returns array of menu item's attributes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
    {
        $menuItemClasses = $this->_getMenuItemClasses($item);
        return ['class' => implode(' ', $menuItemClasses)];
    }

    /**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
    {
        $classes = [];

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }
    public function allowExtension()
    {
      return $this->dataHelper->allowExtension();
    }
    /**
     * Prepare Content HTML
     *
     * @return string
     */
    public function getBlockHtml($id)
    {
        $blockId = $id;
        $html = '';
        if ($blockId)
        {
            $storeId = $this->_storeManager->getStore()->getId();
            /** @var \Magento\Cms\Model\Block $block */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            if ($block->isActive()) {
                $html = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
            }
        }
        return $html;
    }
}
