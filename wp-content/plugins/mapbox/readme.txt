=== MapBox ===
Contributors: miccolis
Tags: maps
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.1.1
License: BSD

MapBox makes it easy to embed beautiful maps in your posts.

== Description ==

Using the MapBox plugin you can quickly and easily embed maps from MapBox Hosting into your site. MapBox hosting is an easy-to-use service for hosting maps. Whether you design custom maps in [TileMill](http://mapbox.com/tilemill/) or use the existing layers in our tileset library, MapBox makes it easy get maps onto your site.

1. Visit [tiles.mapbox.com](http://tiles.mapbox.com) and find the map you want to embed.
2. When viewing the map click the "Embed" button. It's in the bottom right corner of the map.
3. Adjust the height and width of the map in the popup.
4. When you're happy with the map, copy the 'embed code'. It begins with "<iframe".
5. On your Wordpress site add a new post, or edit an existing one.
6. Switch the editor to "HTML" and paste the embed code where you'd like the map.
7. Save, then view your post and enjoy the map!

You may notice that the MapBox plugin converts the embed code into a Wordpress [shortcode](http://en.support.wordpress.com/shortcodes/). The `mapbox` shortcode can take several options, all of which are optional. The order doesn't matter at all.

* `layers` - a comma seperated list of layers. The format of each layer is `{account}.{layer}`.
* `width` - the desired width of the map, in pixels.
* `height` - the desired height of the map, in pixels.
* `lat` - The latitude where the map should be centered.
* `lon` - The longitude where the map should be centered.
* `z` - The 'zoom level' of the map. Zoom level '12' is a street level view, '1' is the view from the moon.
* `options` - Controls and other options, a comma seperated list.

== Installation ==

Upload the Mapbox plugin to your site and activate it in the usual manner.

== Changelog ==

**1.1.1**

	- Bugfix for double quoted shortcode attributes.

**1.1.0**

	- Support the v3 embed API.
	- Properly santize shortcode attributes.

**1.0.0**

	- Initial Release.

