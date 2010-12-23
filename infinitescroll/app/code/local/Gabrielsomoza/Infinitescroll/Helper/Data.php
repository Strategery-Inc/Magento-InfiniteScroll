<?php
/*
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @url        http://gabrielsomoza.com/
 * @category   Local
 * @package    Gabrielsomoza_Infinitescroll
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Gabrielsomoza_Infinitescroll_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getConfigData($node) {
        return Mage::getStoreConfig('infinitescroll/' . $node);
    }
}