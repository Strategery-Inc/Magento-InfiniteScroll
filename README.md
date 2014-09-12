Strategery InfiniteScroll
=====================
This extension is for when the user reaches the end of the current product list, the next page is loaded automatically by AJAX after the end of the list. Easy to install and configure, this module works 100% out of the box with Magento's default theme, and also gives to you the posibility to configurate the custom selectors of your custom theme. Now you can get a more friendly UI by helping the user to save clicks and time.

### Installation

#### Magento Connect (Stable)
1. Download from Magento Connect: http://www.magentocommerce.com/magento-connect/strategery-infinitescroll-2-9213.html
2. Configure the selectos for your theme on System / Configuration section.
3. Refresh your Magento cache.
4. Scroll to infinity and beyond!

#### Composer (Development)
Useful for quickly grabbing development copy.

1. Add the repository to `composer.json`:

    {
        "type": "vcs",
        "url": "https://github.com/Strategery-Inc/Magento-InfiniteScroll.git"
    }

2. Add a requirement:
    `"strategery-inc/magento-infinitescroll": "dev-master"`
3. Run `composer update` to install.

### Configuration
If you have a different theme other than the default, you will need to copy the default theme files to your custom theme folder and configure the plugin by going to System / Configuration / Catalog / Infinite Scroll.

### Demo
<table>
<tr>
<td>Frontend Demo</td>
<td>http://demo.usestrategery.com/infinite-scroll/electronics/all-products.html</td>
</tr>
<tr>
<td>Backend (Admin)</td>
<td>
  <table>
    <tr>
      <td>URL</td>
      <td>http://demo.usestrategery.com/infinite-scroll/admin</td>
    </tr>
    <tr>
      <td>Username</td>
      <td>demo</td>
    </tr>
    <tr>
      <td>Password</td>
      <td>demo1234</td>
    </tr>
  </table>
</td>
</tr>
</table>

### Useful Links
<table>
<tr>
  <td>Repository</td><td>https://github.com/webcreate/infinite-ajax-scroll</td>
</tr>
</table>

### Release Notes
##### v3.0.0
- Changed infinite scroll library (now using: https://github.com/webcreate/infinite-ajax-scroll)
- Improved memory system
- Better jQuery integration
- Added semiautomatic mode (load more button)
- Added support for list mode
- Added optional extra js for advanced customizations (callbacks, extra params, etc)
- Multiple bugfixes

##### v2.1.5/2.1.6
- New features: new options in system / configuration for different instances.
- Fixes: callback function as required option, cache fixed, category event fixed.

##### v2.1.4
- New features: cache, memory function, new options in system / configuration.
- Fixes: layout issues, JS controller issue.

##### v2.0.3
- Added Magento 1.3 support.

On the configuration for default you will see the selectors for Magento 1.6, if you need to configure on 
Magento 1.3 you should set the following selectors:

* Content: div.catalog-listing
* Navigation: table.pager:last, table.view-by:last
* Next: td.pages ol li:last a
* Items: ul.products-grid
* Loading: div.category-products
