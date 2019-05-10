<?php 
/*  # Plugin Name: Comtemeye Appointment
    # Version: 20
    # Description: Manage appointment 
    # Author: Volcus Soft 
    # Author URI: #
    # Plugin URI: #

    #

    # 
    # You should have received a copy of the GNU General Public License
    # along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define("comptoneyedir", plugin_dir_path( __FILE__ ));
define("comptoneyeurl", plugin_dir_url( __FILE__ ));

// Run 'Install' script on plugin activation
register_activation_hook( __FILE__, 'comptoneye_installscript' );
function comptoneye_installscript(){
    require_once('install-script.php');
}

// Translate all text & labels of plugin
add_action('plugins_loaded', 'comptoneye_loadpluginlanguage');
function comptoneye_loadpluginlanguage() {
 load_plugin_textdomain('comptoneye', FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

// Admin dashboard Menu Pages For Booking Calendar Plugin
add_action('admin_menu','comptoneye_calendar_menu');
function comptoneye_calendar_menu() {
    
	//create new top-level menu 'appointment-calendar'
    $menu = add_menu_page('Comptoneye Appointment', __('Comptoneye Appointment', 'comptoneye'), 'administrator', 'appointment-calendar', '', 'dashicons-calendar');
    
	// Calendar Page
    $SubMenu1 = add_submenu_page( 'appointment-calendar', 'Admin Calendar', __('Admin Calendar', 'comptoneye'), 'administrator', 'appointment-calendar', 'comptoneye_display_calendar_page' );
    
	// Time sloat Page
    $SubMenu2 = add_submenu_page( '', 'Manage Time Slot', '', 'administrator', 'time_slot', 'comptoneye_display_time_slot_page' );
    
	// Data Save Page
    $SubMenu3 = add_submenu_page( '', 'Data Save', '', 'administrator', 'data-save', 'comptoneye_display_data_save_page' );
    
	// Service Page
    $SubMenu4 =  add_submenu_page( 'appointment-calendar', 'Services', __('Services', 'comptoneye'), 'administrator', 'service', 'comptoneye_display_service_page' );
    
	// manage Service Page
    $SubMenu5 = add_submenu_page( '', 'Manage Service', '', 'administrator', 'manage-service', 'comptoneye_display_manage_service_page' );
    // Time-Off Page
    $SubMenu6 = add_submenu_page( 'appointment-calendar', 'Time Off', __('Time Off', 'comptoneye'), 'administrator', 'timeoff', 'comptoneye_display_time_off_page' );
    
	// Update Time-Off Page
    $SubMenu7 = add_submenu_page( '', 'Update TimeOff', '', 'administrator', 'update-time-off', 'comptoneye_display_update_time_off_page' );
    
	// Manage Appointment Page
    $SubMenu8 = add_submenu_page( 'appointment-calendar', 'Appointments', __('Appointments', 'comptoneye'), 'administrator', 'manage-appointments', 'comptoneye_display_manage_appointment_page' );
    
	// Update Appointments Page
    $SubMenu9 = add_submenu_page( '', 'Update Appointment', '', 'administrator', 'update-appointment', 'comptoneye_display_update_appointment_page' );
    
	// Settings Page
    $SubMenu10 = add_submenu_page( 'appointment-calendar', 'Settings', __('Settings', 'comptoneye'), 'administrator', 'settings', 'comptoneye_display_settings_page' );
    
	// Export Lists
    $SubMenu17 = add_submenu_page( 'appointment-calendar', 'Export Lists', __('Export Appointments', 'comptoneye'), 'administrator', 'apcal-export-lists', 'comptoneye_display_export_lists_page' );
    
	// // Remove Plugin
 //    $SubMenu14 = add_submenu_page( 'appointment-calendar', 'Remove Plugin Data', __('Remove Plugin Data', 'comptoneye'), 'administrator', 'uninstall-plugin', 'comptoneye_display_uninstall_plugin_page' );
    
	// // Help & Support
 //    $SubMenu16 = add_submenu_page( 'appointment-calendar', 'Help & Support', __('Help & Support', 'comptoneye'), 'administrator', 'help-support', 'comptoneye_display_help_and_support_page' );
    
	// // Get Premium Plugin
 //    $SubMenu15 = add_submenu_page( 'appointment-calendar', 'Get Comptoneye Appointment Premium Plugin', __('Premium Plugin', 'comptoneye'), 'administrator', 'get-premium-plugin', 'comptoneye_display_get_premium_plugin_page' );
    
	// //Get Premium Themes
 //    $SubMenu18 = add_submenu_page( 'appointment-calendar', 'Webriti Premium Themes', __('Premium Themes', 'comptoneye'), 'administrator', 'get-premium-themes', 'comptoneye_display_get_premium_themes_page' );

    add_action( 'admin_print_styles-' . $menu, 'comptoneye_calendar_css_js' );

    //calendar
    add_action( 'admin_print_styles-' . $SubMenu1, 'comptoneye_calendar_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu2, 'comptoneye_calendar_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu3, 'comptoneye_calendar_css_js' );
	
    //service
    add_action( 'admin_print_styles-' . $SubMenu4, 'comptoneye_other_pages_css_js' );
	
    add_action( 'admin_print_styles-' . $SubMenu5, 'comptoneye_other_pages_css_js' );
	
    //time-off
    add_action( 'admin_print_styles-' . $SubMenu6, 'comptoneye_other_pages_css_js' );
	
    add_action( 'admin_print_styles-' . $SubMenu7, 'comptoneye_other_pages_css_js' );
	
    //manage app
    add_action( 'admin_print_styles-' . $SubMenu8, 'comptoneye_other_pages_css_js' );
	
    add_action( 'admin_print_styles-' . $SubMenu9, 'comptoneye_other_pages_css_js' );
	
    //calendar settings
    add_action( 'admin_print_styles-' . $SubMenu10, 'comptoneye_other_pages_css_js' );
	
    //remove plugin
   // add_action( 'admin_print_styles-' . $SubMenu14, 'comptoneye_other_pages_css_js' );
	
    //Get Premium plugin
   // add_action( 'admin_print_styles-' . $SubMenu15, 'comptoneye_other_pages_css_js' );
	
    //help & support
   // add_action( 'admin_print_styles-' . $SubMenu16, 'comptoneye_other_pages_css_js' );
	
    //export lists
    add_action('admin_print_styles-' . $SubMenu17, 'comptoneye_other_pages_css_js');
	
    //get premium themes
   // add_action('admin_print_styles-' . $SubMenu18, 'comptoneye_other_pages_css_js');
	
}//end of menu function

function comptoneye_calendar_css_js() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-custom' );
	wp_enqueue_script( 'jquery-ui-slider' );
    //wp_enqueue_script( 'somejquery', get_template_directory_uri().'/js/jquery-1.10.2.js', array( 'jquery' ));
	
    wp_enqueue_script('full-calendar-min-js', plugins_url('/menu-pages/fullcalendar-assets-new/js/fullcalendar.min.js', __FILE__),array('jquery-ui-datepicker'));
	
    wp_register_style('bootstrap-css', plugins_url('/menu-pages/bootstrap-assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('bootstrap-css');
	
    wp_enqueue_style('full-calendar-css', plugins_url('/menu-pages/fullcalendar-assets-new/css/fullcalendar.css', __FILE__));
    wp_enqueue_style('date-picker-css', plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));

    //font-awesome js n css
    wp_enqueue_style('font-awesome-css', plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__));

}

function comptoneye_other_pages_css_js() {
	// bootstrap css files
    wp_register_style('bootstrap-css', plugins_url('/menu-pages/bootstrap-assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('bootstrap-css');
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-custom' );
	wp_enqueue_script( 'jquery-ui-slider' );
    //wp_enqueue_script( 'somejquery', get_template_directory_uri().'/js/jquery-1.10.2.js', array( 'jquery' ));
			
    wp_enqueue_script('tooltip', plugins_url('/menu-pages/bootstrap-assets/js/bootstrap-tooltip.js', __FILE__));
    wp_enqueue_script('bootstrap-affix', plugins_url('/menu-pages/bootstrap-assets/js/bootstrap-affix.js', __FILE__));
    wp_enqueue_script('bootstrap-application', plugins_url('/menu-pages/bootstrap-assets/js/application.js', __FILE__));

    // font-awesome js n css
    wp_enqueue_style('font-awesome-css', plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__));
	
	// date-picker-css
	wp_enqueue_style('date-picker-css', plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));
	 
	// export
	wp_enqueue_script('jquery-ui-timepicker', plugins_url('/menu-pages/timepicker-assets/js/jquery-ui-timepicker-addon.js', __FILE__),array('jquery-ui-datepicker'));
	wp_enqueue_script('jquery-date', plugins_url('/menu-pages/js/date.js', __FILE__));
	
	// bootstrap js files
	wp_enqueue_script('bootstrap-js', plugins_url('/menu-pages/bootstrap-assets/js/bootstrap.js', __FILE__));
	wp_enqueue_script('bootstrap-min-js', plugins_url('/menu-pages/bootstrap-assets/js/bootstrap.min.js', __FILE__));
	wp_enqueue_script('bootstrap-tab-js', plugins_url('/menu-pages/bootstrap-assets/js/bootstrap-tab.js', __FILE__));
	
}



function comptoneye_short_code_detect() {
    global $wp_query;
    $posts = $wp_query->posts;
    $pattern = get_shortcode_regex();
    foreach ($posts as $post){
		
		if(isset($post->post_content)){
			
			if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'APCAL_PC', $matches[2] ) || in_array( 'APCAL_MOBILE', $matches[2] ) || in_array( 'APCAL', $matches[2] ) ) {
			
				// full-calendar scripts
				wp_enqueue_style('fullcalendar-css', plugins_url('/menu-pages/fullcalendar-assets-new/css/fullcalendar.css', __FILE__));
				
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-custom' );
				wp_enqueue_script( 'jquery-ui-slider' );
				
				wp_enqueue_script('full-calendar', plugins_url('/menu-pages/fullcalendar-assets-new/js/fullcalendar.min.js', __FILE__),array('jquery-ui-datepicker'));
				
				// calendar js
				wp_enqueue_script('calendar', plugins_url('calendar/calendar.js', __FILE__));
				
				// moment min css
				wp_enqueue_script('moment-min', plugins_url('calendar/moment.min.js', __FILE__));
				
				// bootstrap apcal
				wp_enqueue_style('bootstrap-apcal', plugins_url('/menu-pages/bootstrap-assets/css/bootstrap-apcal.css', __FILE__));
				
				// datepicker css
				wp_enqueue_style('datepicker-css', plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));
				break;
			}
		}
		
    }
}
add_action( 'wp', 'comptoneye_short_code_detect' );



//calendar page
function comptoneye_display_calendar_page() {
    require_once('menu-pages/calendar.php');
}
//time slot page
function comptoneye_display_time_slot_page() {
    require_once("menu-pages/appointment-form2.php");
}
//appointment save page
function comptoneye_display_data_save_page() {
    require_once("menu-pages/data-save.php");
}
//service page
function comptoneye_display_service_page() {
    require_once("menu-pages/service.php");
}
//manage service page
function comptoneye_display_manage_service_page() {
    require_once("menu-pages/manage-service.php");
}
//time-off page
function comptoneye_display_time_off_page() {
    require_once("menu-pages/timeoff.php");
}
//update-time-off page
function comptoneye_display_update_time_off_page() {
    require_once("menu-pages/update-time-off.php");
}
//manage-appointment page
function comptoneye_display_manage_appointment_page() {
    require_once("menu-pages/manage-appointments.php");
}
function comptoneye_display_update_appointment_page() {
    require_once("menu-pages/update-appointments.php");
}
//settings page
function comptoneye_display_settings_page() {
    require_once("menu-pages/settings.php");
}
// Remove plugin
function comptoneye_display_uninstall_plugin_page() {
    require_once("reset-plugin.php");
}
//get-premium-plugin page
function comptoneye_display_get_premium_plugin_page() {
    require_once("menu-pages/get-premium-plugin.php");
}
//get-premium-themes page
function comptoneye_display_get_premium_themes_page() {
    require_once("menu-pages/get-premium-themes.php");
}
//help & support page
function comptoneye_display_help_and_support_page() {
    require_once("menu-pages/help-and-support.php");
}
//Export Lists
function comptoneye_display_export_lists_page() {
    require_once("menu-pages/export-lists.php");
}

// Delete all plugin data
function comptoneye_delete_data()
{
	global $wpdb;
	
    //drop ap_appointments table
    $table_appointments = $wpdb->prefix . "ap_appointments";
    $wpdb->query("DROP TABLE `$table_appointments`");

    //drop ap_events table
    $table_events = $wpdb->prefix . "ap_events";
    $events = "DROP TABLE `$table_events`";
    $wpdb->query($events);

    //drop ap_services table
    $table_services = $wpdb->prefix . "ap_services";
    $services = "DROP TABLE `$table_services`";
    $wpdb->query($services);

    //drop a service Category
    $table_service_category = $wpdb->prefix . "ap_service_category";
    $service_category = "DROP TABLE `$table_service_category`";
    $wpdb->query($service_category);

    //delete all default calendar options & settings
    delete_option('apcal_calendar_settings');

    //delete notification details
    delete_option('emailstatus');
    delete_option('emailtype');
    delete_option('emaildetails');

    //delete notification messages
    delete_option('new_appointment_admin_subject');
    delete_option('new_appointment_admin_body');

    delete_option('new_appointment_client_subject');
    delete_option('new_appointment_client_body');

    delete_option('approve_appointment_client_subject');
    delete_option('approve_appointment_client_body');

    delete_option('cancel_appointment_client_subject');
    delete_option('cancel_appointment_client_body');
}

// Including Calendar Short-Code Page
require_once("appointment-calendar-shortcode.php");
require_once("appointment-calendar-mobile.php");