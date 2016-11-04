<?php
/**
 * InfiniteScroll - Magento Integration
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0),
 * available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category   Strategery
 * @package    Strategery_Infinitescroll
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @copyright  Copyright (c) 2014 Strategery Inc. (http://usestrategery.com)
 *
 * @author     Gabriel Somoza (gabriel@strategery.io)
 * @link       https://strategery.io
 *
 * @author     Damian A. Pastorini (damian.pastorini@strategery.io)
 *
 */
class Strategery_Infinitescroll_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_optionsMap;

    /**
     * @param $node
     * @return mixed
     */
    public function getConfigData($node)
    {
        return Mage::getStoreConfig('infinitescroll/' . $node);
    }

    /**
     * @return mixed
     */
    public function isMemoryActive()
    {
        return $this->getConfigData('memory/enabled');
    }

    /**
     * @return mixed
     */
    public function getNextPageNumber()
    {
        return Mage::app()->getRequest()->getParam('p');
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return Mage::getSingleton("core/session");
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig('infinitescroll/general/enabled');
    }

    /**
     * @return string
     */
    public function getCurrentPageType()
    {
        $where = 'grid';
        /** @var Mage_Catalog_Model_Category $currentCategory */
        $currentCategory = Mage::registry('current_category');
        if ($currentCategory) {
            $where = "grid";
            if ($currentCategory->getIsAnchor()) {
                $where = "layer";
            }
        }

        $controller = Mage::app()->getRequest()->getControllerName();
        if ($controller == "result") {
            $where = "search";
        } else if ($controller == "advanced") {
            $where = "advanced";
        }

        return $where;
    }

    /**
     *
     * Check general and instance enable.
     *
     * @return bool
     */
    public function isEnabledInCurrentPage()
    {
        $pageType = $this->getCurrentPageType();
        return $this->isEnabled() && Mage::getStoreConfig('infinitescroll/instances/'.$pageType);
    }

}
