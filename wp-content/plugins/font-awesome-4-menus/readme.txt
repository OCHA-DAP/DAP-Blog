=== Font Awesome 4 Menus ===
Contributors: New Nine
Author URI: http://www.newnine.com
Tags: menus, font awesome, navigation, responsive, nav menu, wp_nav_menu
Requires at least: 3
Tested up to: 3.8
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to add Font Awesome 4 icons to your WordPress menus or anywhere on your site. No programming necessary!

== Description ==

Add Font Awesome 4 icons to your WordPress menus and site without touching a single line of code! With this plugin, just add fa-(icon name) as a class/classes to your menu and the plugin will pull that out, put the icon before or after your link text, and wrap your link text in a span so you can show or hide it as you see fit.

In Font Awesome 4 Menus, we have added the ability to add multiple Font Awesome classes for multiple effects (eg, fa-home fa-fw fa-2x will add a fixed-width home icon at two times the normal size).

In addition, we have given you shortcodes to add icons to your posts and pages, as well as shortcodes to take advantage of the new stacked feature of Font Awesome 4.

== Installation ==

Use the WordPress installer; or, download and unzip the files and put the folder `n9m-font-awesome-4` into the `/wp-content/plugins/` directory.

Then, activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Where can I find all of the available icons? =

Head over to [the Font Awesome website](http://fortawesome.github.io/Font-Awesome/icons "the Font Awesome website") to find a full list of icons available. Don't forget to check out the [examples page](http://fortawesome.github.io/Font-Awesome/examples "examples page") to see how you can mix and match icons for new effects.

= How to I add an icon to my menu? =

Go to Appearance -> Menus, select which menu item to which you want to add the icon, and add the icon class(es) under 'CSS Classes (optional)'. (eg, to add the home icon to your 'Home' link, enter "fa-home" (without quotes) as a class. To make it spin, add "fa-home fa-spin" as your classes.) Save your menu and voila!

Want to add an icon to a post or page? Use the shortcodes available:

* Add a single home icon: [fa class="fa-home"]
* Add a stacked Twitter icon: [fa-stack][fa class="fa-square-o fa-stack-2x"][fa class="fa-twitter fa-stack-1x"][/fa-stack]

You can also use [any of the Font Awesome icons and options](http://fortawesome.github.io/Font-Awesome "any of the Font Awesome icons and options") with the i class in your theme:

* Add a home icon: `<i class="fa fa-home"></i>`
* Add a home icon: `<i class="fa fa-home"></i>`

= How to I place the icon after my menu text? =

Add "fa-after" (excluding the quotes) as one of your classes and the icon will show up after your text.

= Why don't I see an option to add classes? =

Under Appearance -> Menus, click 'Screen Options' (top right of screen) and make sure that 'CSS Classes' is checked. If not - check it!

= Can I hide the text and just show the icons for my menu? =

Yes. Font Awesome menus adds a space between the icon and the text, and wraps that portion in a span with a class of "fontawesome-text". To hide the text and just show the icon, you can put `.fontawesome-text {display: none;}` in your stylesheet.

You can see this in action at our responsive site (http://www.newnine.com) where the mobile and smaller tablet versions only show the icons, but the text then appears on larger displays.

= Will this bloat or slow down my WordPress? =

No. On your site, Font Awesome 4 Menus will load one minified stylesheet (18kb) which loads the fonts. We use it on mobile-first responsive sites (and our own site) all the time without any noticeable performance drag.

= What happens to my menus if I deactivate/uninstall this? =

Your site will be fine. Where you used Font Awesome 4 Menus, those menu items will just have additional classes (fa-whatever) that you can erase or ignore (or style differently).
