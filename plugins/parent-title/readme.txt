=== Parent titles ===
Contributors: queenvictoria
Tags: pages, titles
Requires at least: 3.0
Tested up to: 3.3.2
Stable tag: trunk

Add parent page titles to page titles where they exist..

== Description ==

Add the current page's parents title to the title in the loop and in the head title.

This will occur anywhere a page has a parent regardless of depth. Currently it only goes up one level due to a bug and doesn't play well with other plugins that rewrite the title. See Roadmap for more details on what comes next. *Code contributions welcome.*

== Installation ==

1. Upload `parent-title/` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add a child page to a page

== Frequently Asked Questions ==

= Can I show grandparent page titles too? =

Not currently. See *Roadmap*.

= Can I limit this behaviour to a set of particular parent pages? =

Not currently. See *Roadmap*.

= Can I change or remove the separator used in the page title? =

You can't currently change the separator. Use CSS to hide the separator class. See *Roadmap*.

== Changelog ==

= 0.1 =
* Initial version

== Upgrade Notice ==

== Roadmap ==

* Fix recursion that prevents running apply_filters in the add_filter callback.
* Provide for configuration of limiting which parent pages are affected.
* Provide for configuration of the_title separator.