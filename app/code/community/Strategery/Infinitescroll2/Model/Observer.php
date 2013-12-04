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
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @link       http://gabrielsomoza.com/
 *
 * Update 2.0.0
 * @author     Enrique Piatti (contacto@enriquepiatti.com)
 * @link       http://www.dwdesigner.com/
 */
class Strategery_Infinitescroll2_Model_Observer {

	public function controllerActionPredispatch($event)
	{
		if (Mage::getSingleton('admin/session')->isLoggedIn()) {
			$feedModel = Mage::getModel('infinitescroll2/admin_feed');
			$feedModel->checkUpdate();
		}
	}
}
