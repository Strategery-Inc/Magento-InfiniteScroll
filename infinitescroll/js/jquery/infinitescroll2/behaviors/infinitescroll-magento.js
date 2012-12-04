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
    + https://github.com/paulirish/infinitescroll/
    + version 2.0b2.110617
    + Copyright 2011 Paul Irish & Luke Shumard
    + Licensed under the MIT license
    + Documentation: http://infinite-scroll.com/
*/
(function($){
    $(document).ready(function(){
        $.extend($.infinitescroll.prototype,{
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
            },
			        // Retrieve next set of content items
        retrieve: function infscr_retrieve(pageNum) {

            var instance = this,
            opts = instance.options,
            path = opts.path,
            box, frag, desturl, method, condition,
            pageNum = pageNum || null,
            getPage = (!!pageNum) ? pageNum : opts.state.currPage;
            beginAjax = function infscr_ajax(opts) {

                // increment the URL bit. e.g. /page/3/
                opts.state.currPage++;

                instance._debug('heading into ajax', path);

                // if we're dealing with a table we can't use DIVs
                box = $(opts.contentSelector).is('table') ? $('<tbody/>') : $('<div/>');

                desturl = path.join(opts.state.currPage);
				
				desturl += '&scrollCall=1';

                method = (opts.dataType == 'html' || opts.dataType == 'json' ) ? opts.dataType : 'html+callback';
                if (opts.appendCallback && opts.dataType == 'html') method += '+callback'

                    switch (method) {

                        case 'html+callback':

                            instance._debug('Using HTML via .load() method');
                        box.load(desturl + ' ' + opts.itemSelector, null, function infscr_ajax_callback(responseText) {
                            instance._loadcallback(box, responseText);
                        });

                        break;

                        case 'html':
                            instance._debug('Using ' + (method.toUpperCase()) + ' via $.ajax() method');
                        $.ajax({
                            // params
                            url: desturl,
                            dataType: opts.dataType,
                            complete: function infscr_ajax_callback(jqXHR, textStatus) {
                                condition = (typeof (jqXHR.isResolved) !== 'undefined') ? (jqXHR.isResolved()) : (textStatus === "success" || textStatus === "notmodified");
                                (condition) ? instance._loadcallback(box, jqXHR.responseText) : instance._error('end');
                            }
                        });

                        break;
                        case 'json':
                            instance._debug('Using ' + (method.toUpperCase()) + ' via $.ajax() method');
                        $.ajax({
                            dataType: 'json',
                            type: 'GET',
                            url: desturl,
                            success: function(data, textStatus, jqXHR) {
                                condition = (typeof (jqXHR.isResolved) !== 'undefined') ? (jqXHR.isResolved()) : (textStatus === "success" || textStatus === "notmodified");
                                if(opts.appendCallback) {
                                    // if appendCallback is true, you must defined template in options. 
                                    // note that data passed into _loadcallback is already an html (after processed in opts.template(data)).
                                    if(opts.template != undefined) {
                                        var theData = opts.template(data);
                                        box.append(theData);
                                        (condition) ? instance._loadcallback(box, theData) : instance._error('end');
                                    } else {
                                        instance._debug("template must be defined.");
                                        instance._error('end');
                                    }
                                } else {
                                    // if appendCallback is false, we will pass in the JSON object. you should handle it yourself in your callback.
                                    (condition) ? instance._loadcallback(box, data) : instance._error('end');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                instance._debug("JSON ajax request failed.");
                                instance._error('end');
                            }
                        });

                        break;
                    }
            };

            // if behavior is defined and this function is extended, call that instead of default
            if (!!opts.behavior && this['retrieve_'+opts.behavior] !== undefined) {
                this['retrieve_'+opts.behavior].call(this,pageNum);
                return;
            }


            // for manual triggers, if destroyed, get out of here
            if (opts.state.isDestroyed) {
                this._debug('Instance is destroyed');
                return false;
            };

            // we dont want to fire the ajax multiple times
            opts.state.isDuringAjax = true;

            opts.loading.start.call($(opts.contentSelector)[0],opts);

        }
        });
    });
})(jQuery);