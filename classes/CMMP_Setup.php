<?php
/**
 * File that contains the plugin activation class
 * 
 * @package       CM Membership Plugin
 * 
 * @version       v0.1
 * @since         v0.1
 * @author        Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright     © 2012 Cubic Mushroom Ltd.
 */

if (!class_exists('CMMP_Setup')) :

/**
 * Class to handle plugin activation, deactivation & uninstall
 * 
 * @package       CM Membership Plugin
 * 
 * @version       v0.1
 * @since         v0.1
 * @author        Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright     © 2012 Cubic Mushroom Ltd.
 */
class CMMP_Setup
{
  const DB_VERSION = '1.0';

  // Set this to true to get the state of origin, so you don't need to always uninstall during development.
  const STATE_OF_ORIGIN = false;

  function __construct( $case = false )
  {
    if ( ! $case )
      wp_die( 'Busted! You should not call this class directly', 'Doing it wrong!' );

    switch( $case )
    {
      case 'activate' :
        // add_action calls and else
        # @example:
        add_action( 'init', array( &$this, 'activate_cb' ) );
        break;

      case 'deactivate' : 
        // reset the options
        # @example:
        add_action( 'init', array( &$this, 'deactivate_cb' ) );
        break;

      case 'uninstall' : 
        // delete the tables
        # @example:
        add_action( 'init', array( &$this, 'uninstall_cb' ) );
        break;
    }
  }

  /**
  * Set up tables, add options, etc. - All preparation that only needs to be done once
  */
  function on_activate()
  {
    new CMMP_Setup( 'activate' );
  }

  /**
  * Do nothing like removing settings, etc. 
  * The user could reactivate the plugin and wants everything in the state before activation.
  * Take a constant to remove everything, so you can develop & test easier.
  */
  function on_deactivate()
  {
    $case = 'deactivate';
    if ( STATE_OF_ORIGIN )
      $case = 'uninstall';

    new CMMP_Setup( $case );
  }

  /**
  * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
  * 
  * Will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself
  */
  function on_uninstall()
  {
    // important: check if the file is the one that was registered with the uninstall hook (function)
    if ( __FILE__ != WP_UNINSTALL_PLUGIN )
      return;

    new CMMP_Setup( 'uninstall' );
  }

  function activate_cb()
  {
    global $wpdb, $CMMP;
    $sql = "CREATE TABLE $CMMP->available_number_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      number varchar(32) NOT NULL,
      assigned_to_user_id bigint(20),
      assigned_to_user_at datetime,
      PRIMARY KEY  id (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Add the plugin version number
    add_option("cmmp_db_version", self::DB_VERSION);
  }

  function deactivate_cb()
  {
  }

  function uninstall_cb()
  {
// wp_die( '<h1>This is run on <code>init</code> during uninstallation</h1>', 'Uninstallation hook example' );
// wp_die('Uninstall');
    global $wpdb, $CMMP;
    $wpdb->query("DROP TABLE IF EXISTS $CMMP->available_number_table_name");

    delete_option("cmmp_db_version");
  }

  /**
  * trigger_error()
  * 
  * @param (string) $error_msg
  * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
  * @param unknown_type $error_type
  * @return void
  */
  function error( $error_msg, $fatal_error = false, $error_type = E_USER_ERROR )
  {
    if( isset( $_GET['action'] ) && 'error_scrape' == $_GET['action'] ) 
    {
      echo "{$error_msg}\n";
      if ( $fatal_error )
        exit;
    }
    else 
    {
      trigger_error( $error_msg, $error_type );
    }
  }
}

endif;
?>