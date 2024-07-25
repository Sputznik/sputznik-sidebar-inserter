<?php

class SP_SBINS_ADMIN extends SP_SBINS_BASE {

  private $sidebars;
  private $post_meta_key;
  private $default_sidebar_slug;

  function __construct(){
    $this->post_meta_key        = 'sp_sbins_sidebar';
    $this->default_sidebar_slug = 'sp-sbins-default-sidebar';

    // LIST OF CUSTOM SIDEBARS
    $this->sidebars = array(
      'sp-sbins-default-sidebar'	=> array(
        'name' 				=> __( 'Single Post Inline Widgets', 'sputznik-sidebar-inserter' ),
        'description' => __( 'Appears in the single post content', 'sputznik-sidebar-inserter' )
      )
    );

    /* RETURNS SIDEBAR BASED ON ID */
    add_action( 'sp_sbins_sidebar', function( $sidebar_id ){
    	if( is_active_sidebar( $sidebar_id ) && $sidebar_id ){
    		dynamic_sidebar( $sidebar_id );
      }
    });

    add_action( 'widgets_init', array( $this, 'widgets_init' ) );  // INITIALIZE ALL THE WIDGETS IN THE SIDEBAR
    add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 1 );

  }

  /* GETTER AND SETTER FUNCTIONS */
  function get_post_meta_key(){
    return $this->post_meta_key;
  }

  function get_default_sidebar_slug(){
    return $this->default_sidebar_slug;
  }

  // RETURN LIST OF POST_TYPES ON WHICH THIS METABOX IS TO BE ENABLED
  function get_active_types(){
    $sp_sbins_settings = SP_SBINS_SETTINGS::getInstance();
    $settings = $sp_sbins_settings->get_settings();
    // RETURN IF THE ARRAY IS EMPTY
    if( !isset( $settings['post_types'] ) && empty( $settings['post_types'] ) ) return array();
    return array_keys( $settings['post_types'], 1 ); // RETURN POST_TYPES SLUG WITH VALUE 1
  }
  /* GETTER AND SETTER FUNCTIONS */

  function widgets_init() {
    foreach( $this->sidebars as $id => $sidebar ) {
      $sidebar['id'] = $id;
      $this->register_sidebar( $sidebar );
    }
  }

  function register_sidebar( $sidebar ) {
    register_sidebar( array(
      'name' 			    => $sidebar['name'],
      'id' 			      => $sidebar['id'],
      'description' 	=> $sidebar['description'],
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' 	=> "</aside>",
      'before_title' 	=> '<h3>',
      'after_title' 	=> '</h3>'
    ) );
  }

  function add_meta_box(){
    $active_types = $this->get_active_types();

    // RETURN IF THE POST_TYPES ARRAY IS EMPTY
    if( !$active_types ) return;

    add_meta_box( 'sp_sbins_sidebar', 'Inline Sidebar Settings', array( $this, 'render_meta_box' ), $active_types, 'normal', 'low' );
  }

  function render_meta_box( $post ){
    wp_nonce_field( 'sp_sbins_sidebar_metabox', 'sp_sbins_sidebar_metabox_nonce' );
    include('render-inline-sidebar-box.php');
  }

  function save_meta_box( $post_id ){

    // RETURN IF IT'S AN AUTOSAVE
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // RETURN IF NONCE IS NOT SET
    if ( ! isset( $_POST['sp_sbins_sidebar_metabox_nonce'] ) ) return;

    // RETURN IF NONCE IS NOT VALID
    if ( ! wp_verify_nonce( $_POST['sp_sbins_sidebar_metabox_nonce'], 'sp_sbins_sidebar_metabox' ) ) return;

    // RETURN IF CURRENT POST TYPE IS NOT PRESENT IN THE ACTIVE POST TYPES LIST
    if( !in_array( $_POST['post_type'], $this->get_active_types() ) ) return;

    // UPDATE METAFIELD
    if( isset( $_POST[$this->post_meta_key] ) && $_POST[$this->post_meta_key] ){
      update_post_meta( $post_id, $this->post_meta_key, $_POST[$this->post_meta_key] );
    }

  }

}

SP_SBINS_ADMIN::getInstance();
