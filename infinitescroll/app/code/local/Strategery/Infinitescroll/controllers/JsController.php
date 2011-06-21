<?php
/**
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @link       http://gabrielsomoza.com/
 * @category   Strategery
 * @package    Strategery_Infinitescroll	   
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Strategery_Infinitescroll_JsController extends Mage_Core_Controller_Front_Action {
    public function indexAction() {
        $this->getResponse()->setHeader('Content-Type', 'text/javascript');
        $this->loadLayout();
        $this->renderLayout();
    }
}