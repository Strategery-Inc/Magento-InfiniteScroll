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
 * @copyright  Copyright (c) 2011 Strategery Inc. (http://usestrategery.com)
 * 
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @link       http://gabrielsomoza.com/
 * 
 * Update 2.0.0
 * @author     Damian A. Pastorini (admin@dwdesigner.com)
 * @link       http://www.dwdesigner.com/
 */
class Strategery_Infinitescroll_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_optionsMap;


    public function getConfigData($node) 
	{
        return Mage::getStoreConfig('infinitescroll/' . $node);
    }

	public function isMemoryActive()
	{
		return $this->getConfigData('memory/enabled');
	}
	
//	public function isScrollCall()
//	{
//		$result=false;
//		if(Mage::app()->getRequest()->getParam('scrollCall')==1) {
//			$result=true;
//		}
//		return $result;
//	}
	
	public function getNextPageNumber()
	{
		return Mage::app()->getRequest()->getParam('p');
	}
	
	public function getSession()
	{
		return Mage::getSingleton("core/session");
	}
	


	public function isEnabled()
	{
		return Mage::getStoreConfig('infinitescroll/general/enabled');
	}


	public function getCurrentPageType()
	{
		// TODO: we could do this with the full path to the request directly
		$where = 'grid';
		/** @var Mage_Catalog_Model_Category $currentCategory */
		$currentCategory = Mage::registry('current_category');
		if ($currentCategory) {
			$where = "grid";
			if($currentCategory->getIsAnchor()){
				$where = "layer";
			}
		}
		$controller = Mage::app()->getRequest()->getControllerName();
		if ( $controller == "result"){ $where = "search"; }
		else if ( $controller == "advanced") { $where = "advanced"; }
		return $where;
	}

	/**
	 * check general and instance enable
	 * @return bool
	 */
	public function isEnabledInCurrentPage()
	{
		$pageType = $this->getCurrentPageType();
		return $this->isEnabled() && Mage::getStoreConfig('infinitescroll/instances/'.$pageType);
	}

//	public function getSizeLimitForCurrentPage()
//	{
//		$pageType = $this->getCurrentPageType();
//		return Mage::getStoreConfig('infinitescroll/instances/size_'.$pageType.'');
//	}

}
