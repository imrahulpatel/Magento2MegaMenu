<?php
namespace Jnext\Megamenu\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Catalog data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var CustomerSession
     */
    protected $_customerSession;
    /**
     * ScopeConfigInterface scopeConfig
     *
     * @var scopeConfig
     */
    protected $scopeConfig;
    /**
     * @param CustomerSession $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        CustomerSession $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }
    public function allowExtension() {
        return  $this->scopeConfig->getValue('jnext_mega_config/general/enabledisable', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
    public function getCatLabel1Bgolor() {
        return  $this->scopeConfig->getValue('jnext_mega_config/category_labels/category_label1_bgcolor', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
    public function getCatLabel2Bgolor() {
        return  $this->scopeConfig->getValue('jnext_mega_config/category_labels/category_label2_bgcolor', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
    public function getCatLabel3Bgolor() {
        return  $this->scopeConfig->getValue('jnext_mega_config/category_labels/category_label3_bgcolor', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
    public function getCatLabel4Bgolor() {
        return  $this->scopeConfig->getValue('jnext_mega_config/category_labels/category_label4_bgcolor', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
    public function getCatLabel($label) {
        return  $this->scopeConfig->getValue('jnext_mega_config/category_labels/'.$label, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
}