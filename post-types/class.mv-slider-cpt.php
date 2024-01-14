<?php
if( !class_exists( 'MV_Slider_Post_Type')){

    class MV_Slider_Post_Type{
       function __construct(){
          add_action( 'init', array( $this, 'create_post_type') );
          add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
          add_action( 'save_post', array( $this, 'save_post'), 10, 2);        
          add_filter( 'manage_mv-slider_posts_columns', array( $this, 'mv_slider_cpt_columns') );
          add_action( 'manage_mv-slider_posts_custom_column', array( $this, 'mv_slider_custom_columns'), 10,2 );
          add_filter( 'manage_edit-mv-slider_sortable_columns', array( $this, 'mv_slider_sortable_columns') );
       }
  
       public function create_post_type(){
          register_post_type(
              'mv-slider',
              array(
                  'label' => esc_html__( 'Slider', 'mv-slider' ),
                  'description' => esc_html__( 'Sliders', 'mv-slider' ),
                  'labels' => array(
                      'name' => esc_html__( 'Sliders', 'mv-slider' ),
                      'singular_name' => __( 'Slider', 'mv-slider' ),
                      'all_items'           => esc_html__( 'All Sliders', 'mv-slider' ),
                      'add_new'             => esc_html__( 'Add New Slider', 'mv-slider' ),
                      'edit_item'           => esc_html__( 'Edit Slider', 'mv-slider' ),
                      'featured_image'      => esc_html__( 'Slider image', 'mv-slider' ),
                      'set_featured_image'  => esc_html__( 'Set Slider image', 'mv-slider' ),
                     
                  ),
                  'public' => true,	
                //   'rewrite'     => array( 'slug' => '' ),
                  'supports' => array('title', 'editor', 'thumbnail'),
                  'hierarchical'        => false,
                  'show_ui'             => true,
                  'show_in_menu'        => false,                 
                  'menu_position'       => 5,
                  'show_in_admin_bar'   => true,
                  'show_in_nav_menus'   => true,
                  'can_export'          => true,
                  'has_archive'         => false,
                  'exclude_from_search' => false,
                  'publicly_queryable'  => true,
                  'show_in_rest'        => true,
                  'menu_icon'           => 'dashicons-format-gallery',
              )
          );
       }

       public function mv_slider_cpt_columns( $columns ){
        $columns['mv_slider_link_text'] = esc_html__( 'Link Text', 'mv-slider' );
        $columns['mv_slider_link_url'] = esc_html__( 'Link URL', 'mv-slider' );
        return $columns;
       }

       public function mv_slider_custom_columns( $column, $post_id){
           switch( $column ){
              case 'mv_slider_link_text':
               echo esc_html( get_post_meta( $post_id, 'mv_slider_link_text', true ) );
               break;
               case 'mv_slider_link_url':
                echo esc_html( get_post_meta( $post_id, 'mv_slider_link_url', true ) );
                break;
           }

       }

       public function mv_slider_sortable_columns( $columns ){
        $columns['mv_slider_link_text'] = 'mv_slider_link_text';
        $columns['mv_slider_link_url'] = 'mv_slider_link_url';
        return $columns;
       }

       public function add_meta_boxes($post){
           add_meta_box(
                'mv_slider_metabox',
                __( 'Link Option', 'mv-slider' ),
                array( $this, 'add_inner_meta_boxes' ),
                'mv-slider',
                'normal',
                'high'

           );
       }

       public function add_inner_meta_boxes( $post ){
        require_once( MV_SLIDER_PATH . 'views/mv-slider-metabox.php' );
       }

       public function save_post( $post_id ){
           if( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ){
              $old_link_text  = get_post_meta( $post_id, 'mv_slider_link_text', true );
              $new_link_text  = $_POST[ 'mv_slider_link_text' ];
              $old_link_url  = get_post_meta( $post_id, 'mv_slider_link_url', true );
              $new_link_url  = $_POST[ 'mv_slider_link_url' ];

             
              if( empty($new_link_text)){
                update_post_meta( $post_id, 'mv_slider_link_text', __( 'Add some text', 'mv-slider' )  );
              }else{
                update_post_meta( $post_id, 'mv_slider_link_text', sanitize_text_field($new_link_text), $old_link_text  );
              }
            
              if( empty($new_link_url)){
                update_post_meta( $post_id, 'mv_slider_link_url', '#'  );
              }
              else{
               update_post_meta( $post_id, 'mv_slider_link_url', esc_url_raw($new_link_url), $old_link_url  );
              }
            

           }
       }


    }
  
  
  }
  