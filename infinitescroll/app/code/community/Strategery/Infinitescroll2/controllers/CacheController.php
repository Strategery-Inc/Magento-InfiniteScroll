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
 * @author     Damian A. Pastorini (admin@dwdesigner.com)
 * @link       http://www.dwdesigner.com/
 */
class Strategery_Infinitescroll2_CacheController extends Mage_Core_Controller_Front_Action
{
	
    public function flushAction()
	{
        $result = false;
        try {
            $result = Mage::helper('infinitescroll2')->flushCache();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        echo $result;
    }
	
}