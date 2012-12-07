Strategery InfiniteScroll 2
=====================
This extension is for when the user reaches the end of the current product list, the next page is loaded automatically by AJAX after the end of the list. Easy to install and configure, this module works 100% out of the box with Magento's default theme, and also gives to you the posibility to configurate the custom selectors of your custom theme. Now you can get a more friendly UI by helping the user to save clicks and time.

Installation:
-----
- Download from Magento Connect: http://www.magentocommerce.com/magento-connect/strategery-infinitescroll-2-9213.html
- Configure the selectos for your theme on System / Configuration seccion.
- Refresh your Magento cache.
- Scroll to infinity and beyond!

Configuration:
-----
If you have a different theme other than the default, you will need to copy the default theme files to your custom theme folder and configure the plugin by going to System / Configuration / Catalog / Infinite Scroll.
NOTE: If you have another JS module that apply some behave to the product list remember to use our callback function to assign the JS behave to the products loaded by InfiniteScroll.

Demo:
-----

http://demo.usestrategery.com/infinite-scroll
Admin: http://demo.usestrategery.com/infinite-scroll/admin
User: demo
Password: demo1234

Useful Links
-----
**InfiniteScroll**  
Repository: https://github.com/paulirish/infinite-scroll  
Webpage: http://www.infinite-scroll.com/  

**jQuery Plugin**  
http://www.infinite-scroll.com/infinite-scroll-jquery-plugin/  

<<<<<<< HEAD
**Wordpress Plugin**  
http://www.infinite-scroll.com/installation/  
=======
Wordpress Plugin  
http://www.infinite-scroll.com/installation/  


Release Notes - Version 2.1.4
-----
- New features: cache, memory function, new options in system / configuration.
- Fixes: layout issues, JS controller issue.

Release Notes - Version 2.0.3
-----
Added Magento 1.3 support.

On the configuration for default you will see the selectors for Magento 1.6, if you need to configure on Magento 1.3 you should set this selectors:

Content: div.catalog-listing
Navigation: table.pager:last, table.view-by:last
Next: td.pages ol li:last a
Items: ul.products-grid
Loading: div.category-products
>>>>>>> 2.0
