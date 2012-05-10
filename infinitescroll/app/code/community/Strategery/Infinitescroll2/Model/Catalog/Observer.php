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
		$this->hardReset();
		$helper = Mage::helper('infinitescroll2');
		if(Mage::registry('current_category') && $helper->isMemoryActive())
		{
			// info:
			$event = $observer->getEvent();
			$collection = $event->getCollection();
			$pageId = Mage::registry('current_category')->getId();
			$pageByParam = $helper->getNextPageNumber();
			$lastPageNumber = $collection->getLastPageNumber();
			// actions:
			if(!$helper->isScrollCall())
			{
				if(!Mage::getSingleton('checkout/session')->getData('recursiveCollection'))
				{
					$pageLoaded = $helper->loadMemory($pageId);
					if($pageLoaded>1)
					{
						Mage::getSingleton('checkout/session')->setData('recursiveCollection',true);
						Mage::getSingleton('checkout/session')->setData('pageLoaded',$pageLoaded);
						// default page size save:
						$defaultPageSize = $collection->getPageSize();
						Mage::getSingleton('checkout/session')->setData('defautlPageSize',$defaultPageSize);
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
				if($nextPage>1 && $nextPage<=$lastPageNumber)
				{
					$pageByParam=$nextPage;
				}
				$helper->saveMemory($pageByParam,$pageId);
				$collection->setCurPage($pageByParam);
				Mage::getSingleton('checkout/session')->setData('pageLoaded',$pageByParam);
			}
		}
		return $this;
	}
	
	public function restoreCollection($observer)
	{
		$helper = Mage::helper('infinitescroll2');
		if(Mage::registry('current_category') && $helper->isMemoryActive())
		{
			// info:
			$helper = Mage::helper('infinitescroll2');
			$event = $observer->getEvent();
			$collection = $event->getCollection();
			$pageLoaded = Mage::getSingleton('checkout/session')->getData('pageLoaded');
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
				$toolbar = Mage::app()->getLayout()
					->getBlock('content')
					->getChild('category.products')
					->getChild('product_list')
					->getChild('toolbar');
				/* TOOLBAR MODIFICATIONS: pending add JS modification to get this works.
				$tmpNext = $collection->getPageSize();
				$limits = $toolbar->getAvailableLimit();
				$limits[$tmpNext] = $tmpNext;
				foreach($limits as $L)
				{
					$toolbar->addPagerLimit('grid', $L, $L);
					$toolbar->addPagerLimit('list', $L, $L);
				}
				$toolbar->setCollection($collection)->setData('_current_limit',$tmpNext);
				*/
				$restorePageSize = Mage::getSingleton('checkout/session')->getData('defautlPageSize');
				$collection->setPageSize($restorePageSize);
				Mage::getSingleton('checkout/session')->setData('recursiveCollection',false);
			}
			if($helper->isScrollCall() && $pageLoaded>1 && $pageLoaded<$lastPageNumber)
			{
				Mage::getSingleton('checkout/session')->setData('nextPage',$pageLoaded+1);
			}
			$tmpNext=$pageLoaded+1;
			if($helper->isScrollCall() && $tmpNext>$lastPageNumber)
			{
				die();
			}
		}
		return $this;
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
	
}
