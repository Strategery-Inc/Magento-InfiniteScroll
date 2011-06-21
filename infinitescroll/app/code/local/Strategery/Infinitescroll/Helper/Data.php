<?php
/**
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @link       http://gabrielsomoza.com/
 * @category   Strategery
 * @package    Strategery_Infinitescroll	   
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Strategery_Infinitescroll_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getConfigData($node) {
        return Mage::getStoreConfig('infinitescroll/' . $node);
    }
}