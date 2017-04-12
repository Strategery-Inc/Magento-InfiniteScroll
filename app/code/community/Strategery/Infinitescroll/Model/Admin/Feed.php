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
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @link       http://gabrielsomoza.com/
 *
 * @author     Enrique Piatti (contacto@enriquepiatti.com)
 * @link       http://www.dwdesigner.com/
 */
class Strategery_Infinitescroll_Model_Admin_Feed extends Mage_AdminNotification_Model_Feed
{

	const FEED_URL = 'usestrategery.com/infinite_scroll/feed/';

	public function getFeedUrl()
	{
		if (is_null($this->_feedUrl)) {
//			$this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
//				. self::FEED_URL;

			$this->_feedUrl = 'http://'.self::FEED_URL;

		}
		return $this->_feedUrl;
	}

	public function getLastUpdate()
	{
		return Mage::app()->loadCache('infinitescroll_notifications_lastcheck');
	}

	public function setLastUpdate()
	{
		Mage::app()->saveCache(time(), 'infinitescroll_notifications_lastcheck');
		return $this;
	}


}
