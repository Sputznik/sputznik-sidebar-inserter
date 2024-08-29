<?php

class SP_SIDEBAR_INSERTER extends SP_SBINS_BASE {

  function __construct(){
    add_filter( 'the_content', array( $this, 'sidebar_inserter' ) );
  }

  // GET SIDEBAR CONTENT
  function get_sidebar_content(){
    ob_start();
    do_action( 'sp_sbins_sidebar', 'sp-sbins-default-sidebar' );
    return ob_get_clean();
  }

  function sidebar_inserter( $content ){
    $sp_sbins_admin = SP_SBINS_ADMIN::getInstance();


    // RETURN IF POST CONTENT IS EMPTY
    if( !( strlen( $content ) > 0 ) ) return $content;

    // RETURN IF THE:
    // CURRENT POST TYPE IS NOT PRESENT IN THE ACTIVE TYPES
    // OR IF THE CURRENT POST CONTENT IS NOT IN THE LOOP
    if( !is_singular( $sp_sbins_admin->get_active_types() ) || !in_the_loop() ) return $content;

    $inline_sidebar_content = $this->get_sidebar_content();

    // RETURN IF SIDEBAR CONTENT IS EMPTY
    if( !( strlen( $inline_sidebar_content ) > 0 ) ) return $content;

    // GET POST SIDEBAR META
    $default_sidebar = $sp_sbins_admin->get_default_sidebar_slug();
    $sp_sbins_post_meta = get_post_meta( get_the_ID(), $sp_sbins_admin->get_post_meta_key(), true );

    // SET THE POST_META IF EXISTS ELSE SET DEFAULT VALUE
    if( isset( $sp_sbins_post_meta[$default_sidebar] ) ){
      $sp_sbins_post_meta = $sp_sbins_post_meta[$default_sidebar];
    } else{
      // USED IN OLDER POSTS
      $sp_sbins_post_meta = array(
        'disabled'  => 0,
        'placement' => 4
      );
    }

    $is_disabled = isset( $sp_sbins_post_meta['disabled'] ) ? (bool) $sp_sbins_post_meta['disabled'] : false; // Default false
    $placement = isset( $sp_sbins_post_meta['placement'] ) ? (int) $sp_sbins_post_meta['placement'] : 4; // Default 4

    // echo "<pre>";
    // echo "placement: ".$placement."<br/>";
    // echo "Disabled: ".$is_disabled."<br/>";
    // print_r( $sp_sbins_post_meta );
    // echo "</pre>";

    // RETURN IF SIDEBAR HAS BEEN DISABLED FOR THE CURRENT POST
    if( $is_disabled ) return $content;

    // INSERT THE SIDEBAR BASED ON THE PROVIDED PLACEMENT
    if( $placement === 0 ){
      return $inline_sidebar_content.$content;
    } elseif( $placement < 0 ){
      return $content.$inline_sidebar_content;
    } else {
      return $this->insert_between_content( $content, $placement );
    }

  }

  private function insert_between_content( $post_content, $placement ){

    // GET DOM-DOCUMENT OBJECT OF POST CONTENT
    $doc = $this->get_dom_document( $post_content );

    // GET ALL THE PARAGRAPHS IN THE POST CONTENT
    $paragraphs       = $doc->getElementsByTagName( 'p' );
    $total_paragraphs = $paragraphs->length;
    $paragraph_number = $placement;

    // IF POST CONTENT IS EMPTY CREATE A NEW P-TAG AND INSERT IT IN THE CONTENT
    if( !$total_paragraphs ){
      $newNode = $doc->createElement('p');
      $doc->appendChild( $newNode );
      $total_paragraphs = 1;  // UPDATE THE PARAGRAPH COUNT
    }

    // INSERT AFTER THE PROVIDED PARAGRAPH NUMBER IF $total_paragraphs > $paragraph_number ELSE INSERT AT THE LAST
    $insert_index      = ( $total_paragraphs >= $paragraph_number ) ? $paragraph_number : $total_paragraphs;
    $insert_pos        = $paragraphs->item( $insert_index - 1 ); // Index starts from 0 [0 === 1st element]

    // GET DOM-DOCUMENT OBJECT OF SIDEBAR CONTENT
    $temp_sidebar = $this->get_dom_document( $this->get_sidebar_content() );
    $sidebar_node = $doc->importNode( $temp_sidebar->documentElement, true);

    // INSERT SIDEBAR INTO POST CONTENT
    if( $insert_pos->nextSibling === null ){
      $insert_pos->parentNode->appendChild( $sidebar_node);
    } else {
      $insert_pos->parentNode->insertBefore( $sidebar_node, $insert_pos->nextSibling );
    }

    // UPDATED POST CONTENT
    $html = $doc->saveHTML();

    return $html;
  }

  private function get_dom_document( $content ){
    $doc = new DOMDocument();
    // DOMDocument doesn't support html5 tags. Hence, we are using LIBXML_NOERROR to suppress html5 errors
    $doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_NOERROR );
    return $doc;
  }

}

SP_SIDEBAR_INSERTER::getInstance();
