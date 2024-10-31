<?php
/**
 Plugin Name: Search boxes integration for Booking affiliates
 Version: 0.5.1
 Description: This plugin allows you to insert easily in your wordpress site the search boxes and inspiring search boxes that you have already created in your Booking.com Affiliate account.
 Author: Juan Carlos Moscardó Pérez
 Text Domain: basb_text_domain
 Domain Path: /languages
 @package search-boxes-integration-for-booking-affiliates
 License: GPLv3
  
    Copyright (C) 2016  Juan Carlos Moscardó Pérez

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('BASB_TEXT_DOMAIN', 'basb_text_domain'); 
define('BASB_PLUGIN_NAME', 'Search boxes integration for Booking.com affiliates');
define('BASB_PLUGIN_VERSION', '0.5.1');
define('BASB_PLUGIN_SCRIPT_VERSION', '0.5.1');
define('BASB_PLUGIN_FILE', plugin_basename(__FILE__));
define('BASB_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('BASB_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define('BASB_SETTINGS_OPTIONS', "basb_products_settings");
define('BASB_SETTINGS_OPTIONS_PAGE', 'basb-settings-page');
define('BASB_DEFAULT_AID', 000000);
define('BASB_MAX_PRODUCTS',6);
define("BASB_SHORTCODE","booking_affiliate_box");
define("BASB_WIDGET","booking_affiliate_box_widget");
define("BASB_BOOKING_IFRAME","//www.booking.com/flexiproduct.html");
define("BASB_DEFAULT_POST_TYPE_METABOXES","post, page");

require_once(BASB_PLUGIN_DIR_PATH . "/load-all-classes.php");

BasbInit::initAll();
