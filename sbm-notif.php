<?php
/**
 * Plugin Name: User Submission and Notification Plugin
 * Description: Allows users to submit posts from the front-end and notifies the admin.
 * Version:     1.0.0
 * Author:      Stefano Strippoli
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: user-submission-notification
 */

// Define plugin directory
define('SBM_NOTIF_DIR', plugin_dir_path(__FILE__));

// Include functions file
require_once(SBM_NOTIF_DIR . 'inc/functions.php');

function sbm_notif_create_post_type()
{
  // Array of labels for the custom post type.
  $labels = array(
    'name'                 => _x('Submissions', 'Post Type General Name', 'sbm-notif'),
    'singular_name'        => _x('Submission', 'Post Type Singular Name', 'sbm-notif'),
    'menu_name'            => _x('Submissions', 'Admin Menu General Name', 'sbm-notif'),
    'all_items'            => __('All Submissions', 'sbm-notif'),
    'view_item'            => __('View Submission', 'sbm-notif'),
    'add_new_item'         => __('Add New Submission', 'sbm-notif'),
    'add_new'              => __('Add New', 'sbm-notif'),
    'search_items'         => __('Search Submissions', 'sbm-notif'),
    'not_found'            => __('No Submissions Found', 'sbm-notif'),
    'not_found_in_trash'   => __('No Submissions Found in Trash', 'sbm-notif'),
  );

  // Array of arguments to register the custom post type.
  $args = array(
    'labels'               => $labels,
    'public'               => true,
    'has_archive'          => false,
    'menu_icon'            => 'dashicons-email-alt2',
    'supports'             => array('title'),
    'capability_type'     => 'post',
    'capabilities'        => array(
      'edit_post'           => 'administrator',
      'delete_post'         => 'administrator',
      'create_posts'         => '',
    ),
  );
  register_post_type('submissions', $args);
}

// Function to create admin menu pages (submenu for settings in this case)
function sbm_notif_admin_menu()
{
  // Register submenu with parameters
  add_submenu_page('options-general.php','User Submission and Notification','User Submission and Notification','manage_options','sbm-notif-settings','sbm_notif_settings_page', 1);
}

function sbm_notif_register_settings() {
  register_setting( 'sbm_notif_settings', 'sbm_notif_email', 'sanitize_email' ); // Register setting with validation
  register_setting( 'sbm_notif_settings', 'sbm_recaptcha_site_key', '' ); //recaptcha site key
  register_setting( 'sbm_notif_settings', 'sbm_recaptcha_secret_key', '' ); //recaptcha secret key
}

// Function to enqueue scripts and styles using add action func with a hook
function sbm_notif_enqueue_scripts()
{
  wp_enqueue_style('sbm-notif-style', plugins_url('/inc/css/styles.css', __FILE__), array(), '1.0.0');
  wp_enqueue_script('sbm-notif-script', plugins_url('/inc/js/scripts.js', __FILE__), array('jquery'), '1.0.0', true);

  //localize script for ajax on the form
  wp_localize_script( 'sbm-notif-script', 'ajax', array(
    'url'    => admin_url( 'admin-ajax.php' ),
    'nonce'  => wp_create_nonce( 'sbm_form_nonce' ),
    'action' => 'sbm_notif_submit_form'
) );
}

add_action( 'init', 'sbm_notif_create_post_type' ); // action for creating post type
add_action( 'admin_menu', 'sbm_notif_admin_menu' ); // action for creating menu item
add_action( 'admin_init', 'sbm_notif_register_settings' ); // action for registering settings
add_action( 'admin_enqueue_scripts', 'sbm_notif_enqueue_scripts' ); // Enqueue scripts for admin side
add_action( 'wp_enqueue_scripts', 'sbm_notif_enqueue_scripts' ); // Enqueue scripts for user side

add_action('wp_ajax_nopriv_sbm_notif_submit_form', 'sbm_notif_submit_form'); //Register ajax for user that are not logged in
add_action('wp_ajax_sbm_notif_submit_form', 'sbm_notif_submit_form'); //Register ajax for user that are logged in

require(SBM_NOTIF_DIR . 'inc/meta-boxes.php');
require(SBM_NOTIF_DIR . 'inc/form.php');

?>
