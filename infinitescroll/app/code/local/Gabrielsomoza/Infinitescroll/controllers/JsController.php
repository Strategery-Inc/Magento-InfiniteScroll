<?php
/*
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @url        http://gabrielsomoza.com/
 * @category   Local
 * @package    Gabrielsomoza_Infinitescroll
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Gabrielsomoza_Infinitescroll_JsController extends Mage_Core_Controller_Front_Action {
    public function indexAction() {
        $this->getResponse()->setHeader('Content-Type', 'text/javascript');
        $this->loadLayout();
        $this->renderLayout();
    }
}