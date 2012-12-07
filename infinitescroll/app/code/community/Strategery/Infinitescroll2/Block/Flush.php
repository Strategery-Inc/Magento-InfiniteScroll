<?php
class Strategery_Infinitescroll2_Block_Flush extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        $fromStore = $element->getScopeId();
        $layout = $this->getLayout();
        $flushTag = $element->getOriginalData('flush_tag');
        $flushUrl = Mage::getUrl($element->getOriginalData('flush_url'));
        $button = $layout->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel('Flush Cache')
            ->setOnClick('javascript:executeFlush'.$flushTag.'();');
        $buttonHTML = $button->toHtml();
        $jsFunction = '
        <script type="text/javascript">
        function executeFlush'.$flushTag.'()
        {
            if(confirm("Are you sure?")) {
                new Ajax.Request("'.$flushUrl.'",{
                    method: "get",
                    onSuccess: function(transport){
                        if (transport.responseText=="1"){
                            alert("Cache flushed.");
                        }
                    },
                    onFailure: function (transport){
                        alert("Error");
                    }
                });
            }
        }
        </script>';
        $html = $layout->createBlock('core/text','flush-button')->setText($jsFunction.$buttonHTML)->toHtml();
        return $html;
    }

}
