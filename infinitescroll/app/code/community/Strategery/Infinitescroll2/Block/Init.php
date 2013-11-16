<?php
/**
 * InfiniteScroll2 - Magento Integration
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0),
 * available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category   Strategery
 * @package    Strategery_Infinitescroll2
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @copyright  Copyright (c) 2011 Strategery Inc. (http://usestrategery.com)
 *
 * @author Enrique Piatti
 */ 
class Strategery_Infinitescroll2_Block_Init extends Mage_Core_Block_Template
{

	public function getConfigData()
	{
		$helper = Mage::helper('infinitescroll2');
		$cache = Mage::getSingleton('core/cache');
		$configData = $cache->load("infinitescroll2_configData");
		if ( ! $configData) {
			$configData = $helper->getConfigData('selectors/content');
			$cache->save($configData, "infinitescroll2_configData", array("infinitescroll2"));
		}
		return $configData;
	}

	public function getJsConfig()
	{
		$helper = Mage::helper('infinitescroll2');
		$cache = Mage::getSingleton('core/cache');
		$jsConfig = $cache->load("infinitescroll2_jsConfig");
		if ( ! $jsConfig) {
			$jsConfig = $helper->getJsConfig();
			$cache->save($jsConfig, "infinitescroll2_jsConfig", array("infinitescroll2"));
		}
		return $jsConfig;
	}

	public function isEnabled()
	{
		return Mage::helper('infinitescroll2')->isEnabledInCurrentPage();
	}
	
}
