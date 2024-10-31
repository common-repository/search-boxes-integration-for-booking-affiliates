=== Search boxes integration for Booking affiliates ===
Author: Juan Carlos Moscardó Pérez
Contributors: Juan Carlos Moscardó Pérez
Tags: Booking.com, affiliate, patner, widget, booking, reservations, search box,searchbox, accomodation, search accomodation widget,  integration, search box, hotel, inspiring search box, banner, affiliation programme 
Requires at least: 3.4
Tested up to: 4.4.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

Insert easily in your wordpress site the search and inspiring boxes that you have already created with your Booking.com Affiliate account.

== Description ==

**You have to be Booking.com Affiliate to use this plugin.**

This plugin allows you to insert easily in your wordpress site the search boxes and inspiring search boxes that you have already created in your Booking.com Affiliate account.

Before use this plugin you need to add your search boxes code, already generated in Booking.com Affiliate Partner Centre > Products > Search box.

E.g. The Booking.com code generated looks like:
`
<ins class="bookingaff" data-aid="(booking product id)" data-target_aid="(your affiliate ID)" data-prod="nsb" data-width="200" data-height="200" data-dest_id="-392301" data-dest_type="city">
    <!-- Anything inside will go away once widget is loaded. -->
    <a href="//www.booking.com?aid=(your affiliate ID)">Booking.com</a>
</ins>
<script type="text/javascript">
    (function(d, sc, u) {
      var s = d.createElement(sc), p = d.getElementsByTagName(sc)[0];
      s.type = 'text/javascript';
      s.async = true;
      s.src = u + '?v=' + (+new Date());
      p.parentNode.insertBefore(s,p);
      })(document, 'script', '//aff.bstatic.com/static/affiliate_base/js/flexiproduct.js');
</script>
`

You have to paste the code of the search box product generated in Booking.com Affiliate Partner Centre in any of the textareas named "Search box code" and save it.

After save it, you will see a preview and the shortcode of this search box. 

You could use the Booking.com affiliate search box shortcode generator (button "Add Booking.com search box" will be showed before the texteditor) or use the component "Booking.com affiliate search box" on Visual Composer plugin

Shortcode examples:
Insert the search box #id# as was generated in Booking.com Affiliate Partner Centre
`[booking_affiliate_box id="#id#"]`

Now, imagine we has generated the plugin for size 200px width and 200px height. We can override these values:
`[booking_affiliate_box id="#id#" width="100%"]`
`[booking_affiliate_box id="#id#" width="100%" height="300"]`

Or we want to override or add a destination (hotel, city, ...)
`[booking_affiliate_box id="#id#" width="100%" dest_id="12448" dest_type="hotel"]`
`[booking_affiliate_box id="#id#" width="100%" dest_id="-392301" dest_type="city"]`
`[booking_affiliate_box id="#id#" height="300" dest_id="12448" dest_type="hotel"]`
 
= Features = 

* Do not need to write your AID. **AID is embeded in the generated code**
* Fully compatible with the new product system tracking of Booking.com Affiliate Partner Center
* Manage easyly search boxes and inspiring search boxes.
* With a with search boxes you can cover all the needs of your wordpress site.
* Easy to use. You create you search box design and template on  Booking.com Affiliate Partner Center and copy the code generated and paste it in the plugin settings page.
* Shortcode generator tool.
* Widget customizations
* Widget custumization depending the post or pages in post editor.
* Fully integrated with Visual composer using "Booking.com affiliate search box" component

= Possible customisation =

Please, renember that you could fully customized your search boxes Booking.com Affiliate Partner Center.

* Width. You can add responsive width. E.g.: width= 100%
* Height.
* Destination
* Destination ID (**hotel**, city, district, landmark and region)
* Destination type (**hotel**, city, district, landmark and region)
* Fully customization in shortcode, widget and post editor box.

You can get the destination ID from Booking.com Affiliate Partner Centre > Products > Links

== Installation ==

Please, renember that you must be Booking.com affiliate to use this plugin.

1. Download the plugin from https://wordpress.org/plugins/
2. Login in your Wordpress as admin role.
3. Go to: Plugins > Add New > Upload. 
4. Select the downloaded file and click "Install now".
5. Once installed, you must activate this plugin and go to the settings page of this plugin.
6. Add your search boxes and inspirating search boxes code. In the 'Searchbox code textarea. (one search box code for textarea)

After that, I will be able to:
* Use the shortcodes [booking_affiliate_box id="#id#"] to add any of the search boxes added.
* Add search box widgets and customized them.
* control widgets added from a metabox in POSTs and PAGEs. You can add custom post type in the settings page
* Use the shortcode generator tool (button before text editor) to create dinamically your shortcodes and insert them in the post content.

== Screenshots ==

1. First of all you need to login "Booking.com Affiliate Partner Center" and create your search boxes.
2. Copy and paste the code in the plugin setting page.
3. Use the this tool to add your shortcodes easily.
4. This plugin is also integrated with Visual Composer plugin.
5. Demo page with shortcode samples and widget.

== Changelog ==

= 0.5.1 =
* First release of the plugin