<h3><?php echo ( !empty( $content )) ? esc_html( $content ) : esc_html( MV_Slider_Settings::$options['mv_slider_title'] ); ?></h3>
<?php echo $content; ?>
<div class="mv-slider flexslider">
  <ul class="slides">
    <?php
     $args = array(
      'post_type' => 'mv-slider',
      'post_status' => 'publish',
      'post_in' => $id,
      'orderby' => $orderby
     );

     $my_query = new WP_Query( $args );

     if( $my_query->have_posts()) :
       while( $my_query->have_posts() ) : $my_query->the_post();
       $button_text = get_post_meta( get_the_id(), 'mv_slider_link_text', true );
       $button_url = get_post_meta( get_the_id(), 'mv_slider_link_url', true );
    ?>
    <li>
      <?php
      if( has_post_thumbnail() ){
        the_post_thumbnail( 'full', 
        array( 
          'class' => 'img-fluid',
          ) 
        );
      }else{
        echo "<img src='" . MV_SLIDER_URL . "assets/images/slider-placeholder.jpg' class='img-fluid wp-post-image' />";
      }
      
       
       ?>
        <div class="mvs-container">
            <div class="slider-details-container">
                <div class="wrapper">
                   <div class="slider-title">
                       <h2><?php the_title(); ?></h2>
                   </div>
                   <div class="slider-description">
                      <div class="subtitle"><?php the_content(); ?></div>
                      <a class="link" href="<?php echo esc_attr( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a>
                   </div>
                </div>
            </div>
        </div>
    </li>
    <?php
     endwhile;
      wp_reset_postdata();
    endif;
    
    ?>
  </ul>
</div>