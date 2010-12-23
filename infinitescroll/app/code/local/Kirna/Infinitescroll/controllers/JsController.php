<?php
class Kirna_Infinitescroll_JsController extends Mage_Core_Controller_Front_Action {
    public function indexAction() {
        $this->getResponse()->setHeader('Content-Type', 'text/javascript');
        $this->loadLayout();
        $this->renderLayout();
    }
}