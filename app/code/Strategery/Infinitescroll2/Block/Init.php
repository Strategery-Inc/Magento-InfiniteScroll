<?php
/**
 * Strategery InfiniteScroll2 - Magento 2 Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0),
 * available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @copyright  Copyright (c) 2016 Strategery Inc. (http://www.strategery.io/)
 * @author     Damian A. Pastorini (damian.pastorini@dwdeveloper.com)
 */

namespace Strategery\Infinitescroll2\Block;

class Init extends \Magento\Framework\View\Element\Template
{

	protected $scopeConfig;
	protected $catalogSession;
	protected $registry;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Model\Session $catalogSession,
		\Magento\Framework\Registry $registry,
		array $data = []
	) {
		$this->scopeConfig = $context->getScopeConfig();
		$this->catalogSession = $catalogSession;
		$this->registry = $registry;
		parent::__construct($context, $data);
	}

	public function getConfig($fullPath)
	{
		return $this->scopeConfig->getValue($fullPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getScrollConfig($node)
	{
		return $this->getConfig('strategery_infinitescroll2/' . $node);
	}

	public function isEnabled()
	{
		$enabled = ($this->getScrollConfig('general/enabled') && $this->isEnabledInCurrentPage());
		return $enabled;
	}

	public function isMemoryActive()
	{
		return $this->getScrollConfig('memory/enabled');
	}

	public function getNextPageNumber()
	{
		return $this->getRequest()->getParam('p');
	}

	public function getCurrentPageType()
	{
		$where = 'grid';
		$currentCategory = $this->getCurrentCategory();
		if ($currentCategory) {
			$where = "grid";
			if($currentCategory->getIsAnchor()){
				$where = "layer";
			}
		}
		$controller = $this->getRequest()->getControllerName();
		if ( $controller == "result"){ $where = "search"; }
		else if ( $controller == "advanced") { $where = "advanced"; }
		return $where;
	}

	public function getCurrentCategory()
	{
		return $this->registry->registry('current_category');
	}

	/**
	 * check general and instance enable
	 * @return bool
	 */
	public function isEnabledInCurrentPage()
	{
		$pageType = $this->getCurrentPageType();
		return $this->getScrollConfig('instances/'.$pageType);
	}

	/**
	 * @return bool|false
	 */
	public function getLoaderImage()
	{
		$url = $this->getScrollConfig('design/loading_img');
		if(!empty($url)) {
			$url = strpos($url, 'http') === 0 ? $url : $this->getSkinUrl($url);
		}
		return empty($url) ? false : $url;
	}

	/**
	 * @return string
	 */
	public function getProductListMode()
	{
		// user mode
        $currentMode = $this->getRequest()->getParam('product_list_mode')  ? $this->getRequest()->getParam('product_list_mode') : $this->catalogSession->getDisplayMode();
        if ($currentMode) {
			switch($currentMode){
				case 'list':
					$productListMode = 'list';
					break;
				case 'grid':
				default:
					$productListMode = 'grid';
			}
		} else {
			$defaultMode = $this->getConfig('catalog/frontend/list_mode');
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
