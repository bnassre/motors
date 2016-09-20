<?php /*
Plugin Name: STM Vehicles Listing
Plugin URI: http://stylemixthemes.com/
Description: STM Vehicles Listing
Author: StylemixThemes
Author URI: http://stylemixthemes.com/
Text Domain: stm_vehicles_listing
Version: 2.4
*/
$plugin_path = dirname(__FILE__);
define( 'STM_VEHICLE_LISTING', 'stm_vehicles_listing' );

if(!is_textdomain_loaded('stm_vehicles_listing')) {
    load_plugin_textdomain('stm_vehicles_listing', false, 'stm_vehicles_listing/languages');
}

require_once $plugin_path . '/includes/enqueue.php';

require_once $plugin_path . '/includes/options.php';

//CSV Parser
require_once $plugin_path . '/includes/csv-importer.php';
require_once $plugin_path . '/includes/csv-importer-iframe.php';

// XML Parser
require_once $plugin_path . '/includes/xml-importer.php';

require_once $plugin_path . '/includes/automanager/xml-importer-automanager.php';
require_once $plugin_path . '/includes/automanager/xml-importer-automanager-ajax.php';
require_once $plugin_path . '/includes/automanager/xml-importer-automanager-iframe.php';
require_once $plugin_path . '/includes/automanager/xml-importer-automanager-cron.php';

require_once $plugin_path . '/includes/listing_metaboxes.php';

require_once $plugin_path . '/includes/category-image.php';