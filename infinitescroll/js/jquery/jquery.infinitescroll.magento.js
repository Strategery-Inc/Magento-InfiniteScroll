/**
 * ----------------------------------------------------------
 * Infinite Scroll Behaviour
 * Magento Integration
 * 
 * https://github.com/gabrielsomoza/Magento-InfiniteScroll
 * 
 * @author     Gabriel Somoza (me@gabrielsomoza.com)
 * @link       http://gabrielsomoza.com/
 * @category   Strategery
 * @package    Strategery_Infinitescroll	   
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 * ----------------------------------------------------------
 * 
 * Infinite Scroll v2.0b2.110617
 * https://github.com/paulirish/infinite-scroll
 * version 2.0b2.110617
 * Copyright 2011 Paul Irish & Luke Shumard
 * Licensed under the MIT license
 * 
 * Documentation: http://infinite-scroll.com/
 */
(function ($){
    $.extend($.infinitescroll.prototype,{
	
        _setup_magento: function infscr_setup_magento () {
            var opts = this.options,
            instance = this;
			
            this._binding('bind');
		
        },
	
        _loadcallback_magento: function infscr_loadcallback_magento (box, data) {
            
            var opts = this.options,
                callback = this.options.callback,
                frag;
                
            var result = (opts.isDone || opts.isLastPage) ? 'done' : (!opts.appendCallback) ? 'no-append' : 'append';
            // If true, this will only be used the next time - displaying the last item and allowing the 'done' message to be printed.
            opts.isLastPage = !$(opts.nextSelector, data).length;
       
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
                    // if it didn't return anything
                    if (children.length == 0) {
                        return this._error('end');
                    }
                    
                    // use a documentFragment because it works when content is going into a table or UL
                    frag = document.createDocumentFragment();
                    while (box[0].firstChild) {
                        frag.appendChild(box[0].firstChild);
                    }
                    
                    this._debug('contentSelector', $(opts.contentSelector)[0])
                    
                    $(opts.contentSelector)[0].appendChild(frag);
                    data = children.get();
                    
                    break;
            }

            // loadingEnd function
            opts.loadingEnd.call($(opts.contentSelector)[0],opts)
            
            // smooth scroll to ease in the new content
            if (opts.animate) {
                var scrollTo = $(window).scrollTop() + $('#infscr-loading').height() + opts.extraScrollPx + 'px';
                $('html,body').animate({
                    scrollTop: scrollTo
                }, 800, function () {
                    opts.isDuringAjax = false;
                });
            }

            if (!opts.animate) 
                opts.isDuringAjax = false; // once the call is done, we can allow it again.
            
            callback.call($(opts.contentSelector)[0], data);
        }
    });
})(jQuery);