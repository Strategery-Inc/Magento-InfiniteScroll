# END OF LIFE
This FREE extension for Magento 1.x will be no longer maintained by the original developers and no more support will be provided on the ticketing system or via email. You may still use it, fork it, etc, as usual – of course at your own risk (see license). Thanks to the many thousands of merchants who used the extension, and all the developers who contributed to it over the years!


Strategery InfiniteScroll
=====================
This extension is for when the user reaches the end of the current product list, the next page is loaded automatically by AJAX after the end of the list. Easy to install and configure, this module works 100% out of the box with Magento's default theme, and also gives to you the possibility to configure the custom selectors of your custom theme. Now you can get a more friendly UI by helping the user to save clicks and time.

### Installation

#### Magento Connect (Stable)
1. Download from Magento Connect: https://www.magentocommerce.com/magento-connect/strategery-infinitescroll.html
2. Configure the selectors for your theme on System / Configuration section.
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

#### Trouble Installing?

If you need help with the installation, we offer a commercial installation service. [Request a quote here](https://pipedrivewebforms.com/form/80d2ad83a4283978732a199e556a7ab51252828).

### Configuration
If you have a different theme other than the default, you will need to copy the default theme files to your custom theme folder and configure the plugin by going to System / Configuration / Strategery / InfiniteScroll.

### Demo
<table>
<tr>
<td>Frontend Demo</td>
<td>http://demo.usestrategery.com/infinite-scroll/infinitescroll.html</td>
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

### Development Notes
To hook to window.ias you can listen our custom event "infiniteScrollReady".

If you need to add a custom layered navigation (like AheadWorks Layered Navigation or anything else that makes AJAX calls to update the product list or grid), just make sure to destroy the current IAS object and after that reinitialize SgyIAS by adding the following at the end of the function that calls the new content:
`window.ias.destroy();
SgyIAS.init();`
Of course, you need to update any parameters that you pass to SgyIAS and change with the new content.

------------------
### Release Notes
##### v3.5.3
- Small refactor for Marketplace migration.

##### v3.5.2
- Changed default configuration to prevent issues with jQuery in RWD/default theme.
- Added $j validation to prevent issues with other themes.

##### v3.5.1
- Fixed items height implementing window delayed-resize trigger.

##### v3.5.0
- Updated jQuery IAS.
- Fixed issue with configurable swatches.
- Fixed previous button selector.
- Added previous feature configuration.

##### v3.1.0
Upgraded to IAS 2.1.3, which includes major stability and bug-fix improvements, as well as some new features.

###### UPGRADE NOTES
If you're supplying custom configuration through a file (in the "Advanced" tab of the System Config options) please take a look at the new configuration architecture in this file: http://bit.ly/1wurMmX

If you're listening to certain events, please take a look at the new events API: http://infiniteajaxscroll.com/docs/events.html

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

Copyright 2014 Strategery, Inc. Licensed under the Academic Free License version 3.0
