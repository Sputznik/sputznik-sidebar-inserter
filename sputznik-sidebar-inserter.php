<?php
/*
Plugin Name: Sputznik Sidebar Inserter
Plugin URI: https://sputznik.com
Description: Plugin to insert sidebar above, below or between the post content
Version: 1.0.0
Author: Stephen Anil (Sputznik)
Author URI: https://sputznik.com
Text Domain: sputznik-sidebar-inserter
*/

if( ! defined( 'ABSPATH' ) ){ exit; }

// INCLUDE FILES
$inc_files = array(
  'class-sp-sbins-base.php',
  'lib/class-sp-sbins-settings.php',
  'lib/class-sp-sbins-admin.php',
  'lib/class-sp-sidebar-inserter.php'
);

foreach( $inc_files as $inc_file ){ require_once( $inc_file ); }
