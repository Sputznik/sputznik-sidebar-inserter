<?php

class SP_SBINS_SETTINGS extends SP_SBINS_BASE {

  private $settings;
  private $settings_slug;

  function __construct(){

    $this->settings_slug = 'sp_sbins_settings';

    $this->read_settings();

    add_action( 'admin_menu', array( $this, 'admin_menu' ) ); // SETTINGS PAGE
		add_action( 'plugin_action_links_'.SP_SBINS_BASE, array( $this, 'plugin_action_links' ) ); // PLUGIN ACTION LINKS
    
  }

  private function get_post_types(){
    $post_types = get_post_types( array( '_builtin' => false ) );

    $types = array(
      // 'page' => 'page',
      'post' => 'post'
    );

    $types = array_merge( $types, $post_types );

    // REMOVE POST_TYPES
    unset( $types['orbit-types'] );
    unset( $types['orbit-form'] );
    unset( $types['orbit-tmp'] );
    unset( $types['orbit-fep'] );
    unset( $types['guest-author'] );

    foreach( $types as $type_slug => $type ){
      $type_object = get_post_type_object( $type_slug );

      if( !$type_object->show_ui ){
        unset( $types[ $type_slug ] );
        continue;
      }

      $types[ $type_slug ] = $type_object->label;
    }

    return $types;

  }

  /* GETTER AND SETTER FUNCTIONS */
  function get_settings(){ return $this->settings; }
  function set_settings( $settings ){ $this->settings = $settings; }

  function read_settings(){
    $value = get_option( $this->settings_slug );
    if( !$value || !is_array( $value ) ) {
      $this->set_settings( array() );
      return;
    }
    $this->set_settings( $value );
  }

  function write_settings( $settings ){
    update_option( $this->settings_slug, $settings );
    $this->set_settings( $settings );
  }
  /* GETTER AND SETTER FUNCTIONS */

  function admin_menu(){
    add_options_page(
      __( 'Sputznik Sidebar', 'sputznik-sidebar-inserter' ),
      __( 'Sputznik Sidebar', 'sputznik-sidebar-inserter' ),
      'manage_options',
      'sp-sbins-settings',
      array( $this, 'settings_page' )
    );
  }

  /* SETTINGS PAGE TEMPLATE */
  function settings_page(){
    include( "settings/templates/sp-sbins-settings.php" );
  }

  private function tabs( $screens, $base_url = 'options-general.php?page=sp-sbins-settings', $disable_tab = false ){

    $common_url = admin_url( $base_url );

    $active_tab = '';

    _e('<h2 class="nav-tab-wrapper">');

    foreach ( $screens as $slug => $screen ) {
      $url = $common_url;

      if( isset( $screen['tab'] ) ){
        $url = esc_url( add_query_arg( array('tab' => $screen['tab'] ), $common_url ) );
      }

      $nav_class = "nav-tab";

      if( isset( $screen['tab'] ) && isset( $_GET['tab'] ) && ( $screen['tab'] == $_GET['tab'] ) ){
        $nav_class .= " nav-tab-active";
        $active_tab = $slug;
      }

      if( !isset( $screen['tab'] ) && !isset( $_GET['tab'] ) ){
        $nav_class .= " nav-tab-active";
        $active_tab = $slug;
      }

      echo "<a";
      if( !$disable_tab){ echo " href='$url'"; }
      echo " class='$nav_class'>" . $screen['label'] . "</a>";

    }

    _e('</h2>');

    if( file_exists( $screens[$active_tab]['template'] ) ){
      include $screens[$active_tab]['template'];
    }

  }

  function plugin_action_links( $links ){
    $links['settings'] = '<a href="'.admin_url( 'options-general.php?page=sp-sbins-settings' ).'">'.__( 'Settings', 'sputznik-sidebar-inserter' ).'</a>';
    return $links;
  }

}

SP_SBINS_SETTINGS::getInstance();
