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
class Strategery_Infinitescroll2_Model_Observer
{
	/**
	 * catalog_product_collection_load_before
	 *
	 * @param $observer
	 * @return $this
	 */
	public function modifyCollection($observer)
	{
        // check general and instance enable:
	    $whereare = $this->_whereAreWe();
		/* @var $helper Strategery_Infinitescroll2_Helper_Data */
		$helper = Mage::helper('infinitescroll2');
	    if($helper->isEnabled() && Mage::getStoreConfig('infinitescroll2/instances/'.$whereare))
	    {
		    // reset:
		    $this->_hardReset();
			/** @var Mage_Catalog_Model_Category $category */
			$category = Mage::registry('current_category');
		    if($category && $helper->isMemoryActive())
		    {
				// $cacheName = str_replace('/','_',Mage::app()->getRequest()->getRequestString());
				$cacheName = $category->getId();
				/** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
				$collection = $this->_getCache($observer, $cacheName);
				$lastPageNumber = $collection->getLastPageNumber();

				if($lastPageNumber > 1)
				{
					$pageId = $category->getId();
					$pageByParam = $helper->getNextPageNumber();
					$pageLoaded = $helper->loadMemory($pageId);
					// chek page size or default
					if (Mage::getStoreConfig('infinitescroll2/instances/size_'.$whereare.'')){
						$defaultPageSize = Mage::getStoreConfig('infinitescroll2/instances/size_'.$whereare.'');
					}
					else{
						$defaultPageSize = $collection->getPageSize();
					}

					Mage::getSingleton('checkout/session')->setData('defautlPageSize',$defaultPageSize);
					// actions:
					if( ! $helper->isScrollCall())
					{
						if( ! Mage::getSingleton('checkout/session')->getData('recursiveCollection'))
						{
							if($pageLoaded > 1)
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
						if($pageLoaded > $nextPage)
						{
							$nextPage = $pageLoaded+1;
						}
						if($nextPage > 1 && $nextPage <= $lastPageNumber)
						{
							$pageByParam = $nextPage;
						}
						if($nextPage <= $lastPageNumber)
						{
							$helper->saveMemory($pageByParam, $pageId);
						}
						$collection->setCurPage($pageByParam);
						Mage::getSingleton('checkout/session')->setData('pageLoaded',$pageByParam);
					}
				}
		    }
        }
        return $this;
	}


	/**
	 * catalog_product_collection_load_after
	 *
	 * @param $observer
	 * @return $this
	 */
	public function restoreCollection($observer)
	{
        // check general and instance enable:
        $whereare = $this->_whereAreWe();
		/* @var $helper Strategery_Infinitescroll2_Helper_Data */
		$helper = Mage::helper('infinitescroll2');
        if($helper->isEnabled() && Mage::getStoreConfig('infinitescroll2/instances/'.$whereare))
        {
			$category = Mage::registry('current_category');
		    if($category && $helper->isMemoryActive() )
		    {
				$event = $observer->getEvent();
				/** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
				$collection = $event->getCollection();
				$lastPageNumber = $collection->getLastPageNumber();
				if($lastPageNumber > 1)
				{
					$pageLoaded = Mage::getSingleton('checkout/session')->getData('pageLoaded');
					$nextPageSaved = Mage::getSingleton('checkout/session')->getData('nextPage');
					$tmpNext = $pageLoaded + 1;
					// restore page number:
					$restorePageSize = Mage::getSingleton('checkout/session')->getData('defautlPageSize');
					$collection->setPageSize($restorePageSize);
					Mage::getSingleton('checkout/session')->setData('recursiveCollection',false);
					// last page:
					$lastPageNumber = $collection->getLastPageNumber();
					if($helper->isScrollCall() && $nextPageSaved > $lastPageNumber)
					{
						die();
					}

					// actions:
					if(Mage::getSingleton('checkout/session')->getData('recursiveCollection'))
					{
						if($pageLoaded > 1)
						{
							if($tmpNext <= $lastPageNumber)
							{
								Mage::getSingleton('checkout/session')->setData('nextPage', $tmpNext);
							}
							$collection->setCurPage($pageLoaded);
						}
					}
					if($helper->isScrollCall() && $pageLoaded > 1 && $pageLoaded <= $lastPageNumber)
					{
						Mage::getSingleton('checkout/session')->setData('nextPage', $tmpNext);
					}
				}
		    }
        }
        return $this;
	}
	
	protected function _hardReset()
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
