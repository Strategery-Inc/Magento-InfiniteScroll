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
 * @author     Miguel Balparda
 */
class Strategery_Infinitescroll2_Block_Config extends Mage_Core_Block_Text
{
	
    public function setJsText($text)
    {   
        $url    =   Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).$text;
        $val    =   "<script type='text/javascript' src='$url'></script>";
        $this->setData('text', $val);
        return $this;
    }
    
}    
