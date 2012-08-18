<?php
/*
Plugin Name: Cubic Mushroom Membership Numbers plugin
Plugin URI: http://cubicmushroom.co.uk
Description: A plugin to allow users to link user accounts with membership numbers
Version: 1.0
Author: Toby Griffiths - Cubic Mushroom Ltd.
Author URI: http://cubicmushroom.co.uk
License: GPL2
*/

/*  Copyright 2012  Cubic Mushroom Ltd.  (email : support@cubicmushroom.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once plugin_dir_path( __FILE__ ).'classes/CMMP_Setup.php';

/**
 * Class to namespace the plugin functionality
 * 
 * @package       CM Membership Plugin
 * 
 * @since         v0.1
 * @author        Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright     © 2012 Cubic Mushroom Ltd.
 */
class CMMembershipPlugin
{

  const PLUGIN_VERSION = '1.0';
  const AVAILABLE_NUMBERS_TABLE_NAME = 'CMMembershipPlugin';

  /**
   * Property to store the actual table name
   * @var     string
   */
  private $available_number_table_name;

  function __construct()
  {
    global $wpdb;
    $this->available_number_table_name = $wpdb->prefix . self::AVAILABLE_NUMBERS_TABLE_NAME;

    // Plugin activation
    register_activation_hook(__FILE__, array($this, 'install'));

    // Initialise the plugin
    add_action('init', array($this, 'init'));
  }

  function init()
  {

  }

  function numberField()
  {
    ?>
    <label for="cmmp_membership_no"><?php __('Membership no.', 'cmmp'); ?></label>
    <input id="cmmp_membership_no" name="cmmp[membership_no]" type="test" />
    <?php
  }

}

global $CMMP;
$CMMP = new CMMembershipPlugin();

register_activation_hook( __FILE__, array( 'CMMP_Setup', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'CMMP_Setup', 'on_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'CMMP_Setup', 'on_uninstall' ) );
?>