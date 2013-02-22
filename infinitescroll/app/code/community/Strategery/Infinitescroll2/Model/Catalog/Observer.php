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
class Strategery_Infinitescroll2_Model_Catalog_Observer
{
	
	public function modifyCollection($observer)
	{
        // check general and instance enable:
	    $whereare = $this->_whereAreWe();
	    if(Mage::getStoreConfig('infinitescroll2/general/enable') && Mage::getStoreConfig('infinitescroll2/instances/'.$whereare))
	    {
		    // reset:
		    $this->hardReset();
		    // helper:
		    $helper = Mage::helper('infinitescroll2');
		    // observer data:
		    $event = $observer->getEvent();
            $cacheName = str_replace('/','_',Mage::app()->getRequest()->getRequestString());
            if(Mage::registry('current_category'))
            {
                $cacheName = Mage::registry('current_category')->getId();
            }
            $collection = $this->_getCache($observer, $cacheName);
		    $lastPageNumber = $collection->getLastPageNumber();
		    if(Mage::registry('current_category') && $helper->isMemoryActive() && $lastPageNumber>1)
		    {
			    // info:
			    $pageId = Mage::registry('current_category')->getId();
			    $pageByParam = $helper->getNextPageNumber();
			    $pageLoaded = $helper->loadMemory($pageId);
			    // chek page size or default
			    if (Mage::getStoreConfig('infinitescroll2/instances/size_'.$whereare.''))
        			$defaultPageSize = Mage::getStoreConfig('infinitescroll2/instances/size_'.$whereare.'');			
			    else
        			$defaultPageSize = $collection->getPageSize();
        			
			    Mage::getSingleton('checkout/session')->setData('defautlPageSize',$defaultPageSize);
			    // actions:
			    if(!$helper->isScrollCall())
			    {
				    if(!Mage::getSingleton('checkout/session')->getData('recursiveCollection'))
				    {
					    if($pageLoaded>1)
					    {
						    Mage::getSingleton('checkout/session')->setData('recursiveCollection',true);
						    Mage::getSingleton('checkout/session')->setData('pageLoaded',$pageLoaded);
						    // replace page size:
						    $tmpPageSize = $defaultPageSize*$pageLoaded;
						    $collection->setPageSize($tmpPageSize);
					    }
					    else
					    {
						    Mage::getSingleton('checkout/session')->setData('pageLoaded','');
						    Mage::getSingleton('checkout/session')->setData('nextPage','');
					    }
				    }
			    }
			    else
			    {
				    $nextPage = Mage::getSingleton('checkout/session')->getData('nextPage');
				    if($pageLoaded>$nextPage)
				    {
					    $nextPage = $pageLoaded+1;
				    }
				    if($nextPage>1 && $nextPage<=$lastPageNumber)
				    {
					    $pageByParam=$nextPage;
				    }
				    if($nextPage<=$lastPageNumber)
				    {
					    $helper->saveMemory($pageByParam,$pageId);
				    }
				    $collection->setCurPage($pageByParam);
				    Mage::getSingleton('checkout/session')->setData('pageLoaded',$pageByParam);
			    }
		    }
		    return $this;
        }
	}
	
	public function restoreCollection($observer)
	{
        // check general and instance enable:
        $whereare = $this->_whereAreWe();
        if(Mage::getStoreConfig('infinitescroll2/general/enable') && Mage::getStoreConfig('infinitescroll2/instances/'.$whereare))
        {
		    // helper:
		    $helper = Mage::helper('infinitescroll2');
		    // observer data:
		    $event = $observer->getEvent();
            $collection = $event->getCollection();
		    $lastPageNumber = $collection->getLastPageNumber();
		    if(Mage::registry('current_category') && $helper->isMemoryActive() && $lastPageNumber>1)
		    {
			    // info:
			    $pageLoaded = Mage::getSingleton('checkout/session')->getData('pageLoaded');
			    $nextPageSaved = Mage::getSingleton('checkout/session')->getData('nextPage');
			    $tmpNext = false;
			    // restore page number:
			    $restorePageSize = Mage::getSingleton('checkout/session')->getData('defautlPageSize');
			    $collection->setPageSize($restorePageSize);
			    Mage::getSingleton('checkout/session')->setData('recursiveCollection',false);
			    // last page:
			    $lastPageNumber = $collection->getLastPageNumber();
			    // actions:
			    if(Mage::getSingleton('checkout/session')->getData('recursiveCollection'))
			    {
				    if($pageLoaded>1)
				    {
					    $tmpNext=$pageLoaded+1;
					    if($tmpNext<=$lastPageNumber)
					    {
						    Mage::getSingleton('checkout/session')->setData('nextPage',$tmpNext);
					    }
					    $collection->setCurPage($pageLoaded);
				    }
			    }
			    if(!$tmpNext)
			    {
				    $tmpNext=$pageLoaded+1;
			    }
			    if($helper->isScrollCall() && $nextPageSaved>$lastPageNumber)
			    {
				    die();
			    }
			    if($helper->isScrollCall() && $pageLoaded>1 && $pageLoaded<=$lastPageNumber)
			    {
				    Mage::getSingleton('checkout/session')->setData('nextPage',$tmpNext);
			    }
		    }
		    return $this;
        }		    
	}
	
	public function hardReset()
	{
		if(Mage::app()->getRequest()->getParam('resetAll'))
		{
			$helper = Mage::helper('infinitescroll2');
			$helper->getSession()->setData('defautlPageSize','');
			$helper->getSession()->setData('pageLoaded','');
			$helper->getSession()->setData('nextPage','');
			$helper->getSession()->setData('recursiveCollection','');
			$helper->getSession()->setData('infiniteScroll','');
		}
	}

	public function refreshCache($observer)
	{
        if (Mage::app()->getRequest()->getParam("section") == "infinitescroll2") {
            Mage::helper('infinitescroll2')->flushCache();
        }
	}

    protected function _whereAreWe()
    {
        if (Mage::registry('current_category')) { $where = "grid"; }
        if(is_object(Mage::registry('current_category')) && Mage::registry('current_category')->getIsAnchor()) {
            $where = "layer";
        }
        if (Mage::app()->getRequest()->getControllerName() == "result"){ $where = "search"; }
        if (Mage::app()->getRequest()->getControllerName() == "advanced") { $where = "advanced"; }
        return $where;
    }
    
    protected function _getCache ($observer, $categoryId)
    {
        $collection = $observer->getCollection();
        if(Mage::helper('infinitescroll2')->isCacheEnabled())
        {
            $cache = Mage::getSingleton('core/cache');
            if ($cacheCollection = $cache->load("infinitescroll2_collection_".$categoryId)) {
                $collection = $cacheCollection;
            }
            else {
                $cache->save($collection, "infinitescroll2_collection_".$categoryId,array('infinitescroll2'));
            }
        }
        return $collection;
    }
	
}
