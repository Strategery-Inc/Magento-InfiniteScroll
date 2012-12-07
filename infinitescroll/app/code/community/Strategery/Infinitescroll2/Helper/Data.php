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
 * @author     Damian A. Pastorini (admin@dwdesigner.com)
 * @link       http://www.dwdesigner.com/
 */
class Strategery_Infinitescroll2_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_optionsMap;

    public function __construct() 
	{
        $this->_optionsMap = array(
			'loading' => array
			(
				'data' => '', 
				'type' => 'object',
				'sub-items' => array
				(
					'finishedMsg'=>array('data' => 'design/done_text', 'type' => 'string'),
					'img'=>array('data' => 'design/loading_img', 'type' => 'string'),
					'msgText'=>array('data' => 'design/loading_text', 'type' => 'string'),
					'selector'=>array('data' => 'selectors/loading', 'type' => 'string-JSIE8FIX'),
				)
			),
            'navSelector' => array('data' => 'selectors/navigation', 'type' => 'string'),
            'nextSelector' => array('data' => 'selectors/next', 'type' => 'string'),
            'itemSelector' => array('data' => 'selectors/items', 'type' => 'string'),
            'debug' => array('data' => 'general/debug', 'type' => 'boolean'),
            'animate' => array('data' => 'design/animate', 'type' => 'boolean'),
            'extraScrollPx' => array('data' => 'design/extra_scroll_px', 'type' => 'integer'),
            'doneText' => array('data' => 'design/done_text', 'type' => 'string'),
            'bufferPx' => array('data' => 'design/buffer_px', 'type' => 'integer'),
            'callback' => array('data' => 'callbacks/processed_callback', 'type' => 'function'),
			'behavior' => array('data' => 'magento', 'type'=>'literal-JSIE8FIX')
		);
    }

    public function getConfigData($node) 
	{
        return Mage::getStoreConfig('infinitescroll2/' . $node);
    }

    public function getJsConfig($optionsMap=false) 
	{
		if(!$optionsMap){
			$optionsMap=$this->_optionsMap;
		}
		$result='';
        foreach ($optionsMap as $jsOption => $config) {
			$colon=',';
			$jsIE8Fix=strpos($config['type'],'-JSIE8FIX');
			if($jsIE8Fix!==false){
				$colon=''; 
				$config['type']=substr($config['type'],0,$jsIE8Fix);
			}
            if ($value = $this->getConfigData($config['data']) || $config['type']=='object' || $config['type']=='literal') {
                switch ($config['type']) {
                    case 'string':
                        $value = '"' . $this->getConfigData($config['data']) . '"'; // wrap in double quotes
                        break;
                    case 'boolean':
                        $value = $value == 1 ? 'true' : 'false';
                        break;
					case 'object':
						$value='""';
						if(is_array($config['sub-items']))
						{
							$value="{\n";
							foreach($config['sub-items'] as $name=>$subItem)
							{
								$value .= $this->getJsConfig(array($name=>$subItem));
							}
							$value.='}';
						}
						break;
					case 'literal':
						$value = '"' . $config['data'] . '"';
						break;
                    default:
						// nothing
                }
				$result .= "'{$jsOption}': {$value}$colon\n";
            }
        }
        return $result;
    }
	
	public function isMemoryActive()
	{
		return $this->getConfigData('memory/enabled');
	}
	
	public function isScrollCall()
	{
		$result=false;
		if(Mage::app()->getRequest()->getParam('scrollCall')==1) {
			$result=true;
		}
		return $result;
	}
	
	public function getNextPageNumber()
	{
		return Mage::app()->getRequest()->getParam('p');
	}
	
	public function isMemoryEnableForEachPage()
	{
		return $this->getConfigData('memory/each_page');
	}
	
	public function hasMemoryLimit()
	{
		$result = false;
		if($this->getConfigData('memory/limit') && $this->getConfigData('memory/limit')>1) {
			$result=$this->getConfigData('memory/limit');
		}
		return $result;
	}
	
	public function getSession()
	{
		return Mage::getSingleton("core/session");
	}
	
	public function initMemory()
	{
		$result = false;
		if($this->getSession()->setData('infiniteScroll',array())) {
			$result=true;
		}
		return $result;
	}
	
	public function saveMemory($pageNumber,$page=false)
	{
		$data = $this->getSession()->getData('infiniteScroll');
		if($page!=false && $this->isMemoryEnableForEachPage()) {
			$data[$page] = $pageNumber;
		}
		else {
			$data['generic']=$pageNumber;
		}
		$this->getSession()->setData('infiniteScroll',$data);
	}
	
	public function loadMemory($page=false)
	{
		$result = false;
		$data=$this->getSession()->getData('infiniteScroll');
		if($page!=false && $this->isMemoryEnableForEachPage()) {
			$result = $data[$page];
		}
		else {
			$result = $data['generic'];
		}
		return $result;
	}

    public function isCacheEnabled()
    {
        return $this->getConfigData('cache/enabled');
    }

    public function flushCache()
    {
        $result = false;
        try {
            Mage::getModel('core/design_package')->cleanMergedJsCss();
            Mage::dispatchEvent('clean_media_cache_after');
            $cache = Mage::getSingleton('core/cache');
            $cache->flush("infinitescroll2");
            $result = '1';
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        return $result;
    }
	
}