<?php

class Kirna_Infinitescroll_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getConfigData($node) {
        return Mage::getStoreConfig('infinitescroll/' . $node);
    }
}