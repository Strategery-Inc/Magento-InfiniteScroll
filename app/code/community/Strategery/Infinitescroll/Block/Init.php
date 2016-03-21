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
 * @author Enrique Piatti
 */ 
class Strategery_Infinitescroll_Block_Init extends Mage_Core_Block_Template
{

	public function getConfigData()
	{
		$helper = Mage::helper('infinitescroll');
		$cache = Mage::app()->getCache();
		$configData = $cache->load("infinitescroll_configData");
		if ( ! $configData) {
			$configData = $helper->getConfigData('selectors/content');
			$cache->save($configData, "infinitescroll_configData", array("infinitescroll"));
		}
		return $configData;
	}

	public function isEnabled()
	{
		return Mage::helper('infinitescroll')->isEnabledInCurrentPage();
	}

	/**
	 * @return bool|false
	 */
	public function getLoaderImage()
	{
		$url = Mage::helper('infinitescroll')->getConfigData('design/loading_img');
		if(!empty($url)) {
			$url = strpos($url, 'http') === 0 ? $url : $this->getSkinUrl($url);
		}
		return empty($url) ? false : $url;
	}

	public function getProductListMode()
	{
		// user mode
        $currentMode = $this->getRequest()->getParam('mode') ? $this->getRequest()->getParam('mode') : Mage::getSingleton('catalog/session')->getDisplayMode();
        if ($currentMode) {
			switch($currentMode){
				case 'grid':
					$productListMode = 'grid';
					break;
				case 'list':
					$productListMode = 'list';
					break;
				default:
					$productListMode = 'grid';
			}
		}
		else {
			$defaultMode = Mage::getStoreConfig('catalog/frontend/list_mode');
			switch($defaultMode){
				case 'grid-list':
					$productListMode = 'grid';
					break;
				case 'list-grid':
					$productListMode = 'list';
					break;
				default:
					$productListMode = $defaultMode;
			}
		}

		return $productListMode;
	}

}
