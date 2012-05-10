/*
 * InfiniteScroll - Magento Integration
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
 * @author     Nicolas Far (nicolas.far@usestrategery.com)
     
    --------------------------------
    Infinite Scroll Behavior
    Behavior for Magento
    --------------------------------
    INFINITE SCROLL:
    + https://github.com/paulirish/infinitescroll2/
    + version 2.0b2.110617
    + Copyright 2011 Paul Irish & Luke Shumard
    + Licensed under the MIT license
    + Documentation: http://infinite-scroll.com/
*/
(function($){
    $(document).ready(function(){
        $.extend($.infinitescroll2.prototype,{
            _loadcallback_magento: function infscr_loadcallback_magento (box,data) {
                var opts = this.options,
                    callback = this.options.callback, // GLOBAL OBJECT FOR CALLBACK
                    result = ( opts.wasDoneLastTime || opts.isDone) ? 'done' : (!opts.appendCallback) ? 'no-append' : 'append',
                    originalData = data,
                    frag;
                switch (result) {
                    case 'done':
                        this._showdonemsg();
                        return false;
                        break;
                    case 'no-append':
                        if (opts.dataType == 'html') {
                            data = '<div>' + data + '</div>';
                            data = $(data).find(opts.itemSelector);
                        };
                        break;
                    case 'append':
                        var children = box.children();
                        if (children.length == 0) {
                            return this._error('end');
                        }
                        frag = document.createDocumentFragment();
                        while (box[0].firstChild) {
                            frag.appendChild(box[0].firstChild);
                        }
                        this._debug('contentSelector', $(opts.contentSelector)[0])
                        $(opts.contentSelector)[0].appendChild(frag);
                        data = children.get();
                        break;
                }
                opts.loading.finished.call($(opts.contentSelector)[0],opts);
                if (opts.animate) {
                    var scrollTo = $(window).scrollTop() + $('#infscr-loading').height() + opts.extraScrollPx + 'px';
                    $('html,body').animate({
                        scrollTop: scrollTo
                    }, 800, function () {
                        opts.state.isDuringAjax = false;
                    });
                }
                if (!opts.animate) opts.state.isDuringAjax = false;
                // detect last page in Magento catalog
                if ($(this.options.nextSelector,originalData).length==0){ 
                    this.options.wasDoneLastTime=true;
                }
                callback.call($(opts.contentSelector)[0], data);
            }
        });
    });
})(jQuery);