<?php 
/**
 * Template for testimonial
 *
 * @package templatesnext Shortcode
 */
 	
	$testi_name = esc_attr(rwmb_meta('ispirit_testi_name'));
	$testi_desig = esc_attr(rwmb_meta('ispirit_testi_desig'));
	$testi_company = esc_attr(rwmb_meta('ispirit_testi_company'));
	$testi_class = "";
	
	 if( has_post_thumbnail() && ! post_password_required() )
	 {
		 $testi_class = "with-thumb";
	 }

?>

                
<div id="post-<?php the_ID(); ?>" class="nx-testimonials <?php echo $testi_class; ?>">
    <div class="testi-content">
        
        <?php

            if( has_post_thumbnail() && ! post_password_required() )
            {
				?>
                <div class="testi-thumbnail">
                <?php
					the_post_thumbnail('thumb-square');
				?>
                </div>
                <?php
            }   
        ?>
		<div class="testi-container">
            <span class="nx-qtt-start"><i class="fa fa-quote-left"></i></span>
            <?php
                $exc_length = 60;
                echo isprit_excerpt($exc_length);
            ?>
            <span class="nx-qtt-end"><i class="fa fa-quote-right"></i></span>
        </div>
    </div>
    
    <div class="testi-meta">
    	<?php if($testi_name) { ?>
        <span class="testi-name">
        	<?php
				echo $testi_name;
            ?>
        </span>
        <?php } ?>
        
        <?php if($testi_desig) { ?>
        <span class="testi-desig">
        	<?php
				echo $testi_desig;
            ?>
        </span>
        <?php } ?>
        
        <?php if($testi_company) { ?>
        <span class="testi-company">
        	<?php
				echo $testi_company;
            ?>
        </span>
        <?php } ?>
    </div>    
    
</div><!-- #post -->
