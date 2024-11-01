<?php
    defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); //prevent direct access
    
    $wp_page_types = page_types();
    $sendmsgs_include = get_option( 'sendmsgs_include', 'all' );
    $sendmsgs_exclude = get_option( 'sendmsgs_exclude', 0 );

    $instance_include = get_option('sendmsgs_page_options_inc',serialize(array()));
    $instance_exclude = get_option('sendmsgs_page_options_exc',serialize(array()));

    if(!is_array($instance_include))
    {
        $instance_include = unserialize($instance_include);
    }

    if(!is_array($instance_exclude))
    {
        $instance_exclude = unserialize($instance_exclude);
    }

    $pages = get_posts( array(
            'post_type' => 'page', 'post_status' => 'publish',
            'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC',
            'fields' => array('ID', 'name'),
        ));
   
    $cats = get_categories( array(
            'hide_empty'    => false,
        ) );

    $page_list = new sendmsgs_Walker_Page_List();
?>   
    <p class="description">Select whether you want to load the above scripts on All Pages or Few of them. You can exclude or include the pages by selecting proper option and the pages, posts and categories etc.</p>
    <p>
        <input type="radio" name="sendmsgs_include" id="sendmsgs_include_all" value="all" <?php echo checked( $sendmsgs_include, 'all' ); ?> /> <label for="sendmsgs_include_all"><?php _e( 'Include All', 'sendmsgs' ) ?></label> &nbsp; &nbsp; &nbsp; 
    </p>    
    <div class="sendmsgs_exclude_option">
        <input type="checkbox" name="sendmsgs_exclude" id="sendmsgs_exclude" value="1" <?php echo checked( $sendmsgs_exclude, 1 ); ?>  /> <label for="sendmsgs_exclude"><?php _e( 'Exclude in Specific Pages', 'sendmsgs' ) ?></label>
        <div class="sendmsgs-page-options sendmsgs-page-options_exc_box" >
            <h4 class="sendmsgs_toggle" style="cursor:pointer;"><?php _e( 'Pages') ?> </h4>
            <div class="sendmsgs_collapse">
            <?php 
            foreach ( $pages as $page ) {
                $instance_exclude[ 'page-' . $page->ID ] = isset( $instance_exclude[ 'page-' . $page->ID ] ) ? $instance_exclude[ 'page-' . $page->ID ] : false;
            }

            // use custom Page Walker to build page list
            $args = array( 'instance' => $instance_exclude, 'option_type' => 'sendmsgs_page_options_exc' );
            $page_list_walked = $page_list->walk( $pages, 0, $args );
            if ( $page_list_walked ) {
                echo '<ul>' . $page_list_walked . '</ul>';
            }
            ?>
            </div>
              
            <h4 class="sendmsgs_toggle" style="cursor:pointer;"><?php _e( 'Categories') ?> </h4>
            <div class="sendmsgs_collapse">
                <?php $instance_exclude['cat-all'] = isset( $instance_exclude['cat-all'] ) ? $instance_exclude['cat-all'] : false; ?>
                <p><input class="checkbox" type="checkbox" <?php  checked( $instance_exclude['cat-all'], 'on' ); ?> id="sendmsgs_page_options_exc-cat-all" name="sendmsgs_page_options_exc[cat-all]" />
                <label for="sendmsgs_page_options_exc-cat-all"><?php _e( 'All Categories', 'sendmsgs' ); ?></label></p>
            <?php
                foreach ( $cats as $cat ) {
                    $instance_exclude[ 'cat-' . $cat->cat_ID ] = isset( $instance_exclude[ 'cat-' . $cat->cat_ID ] ) ? $instance_exclude[ 'cat-' . $cat->cat_ID] : false;
            ?>
                <p><input class="checkbox" type="checkbox" <?php  checked( $instance_exclude['cat-'. $cat->cat_ID], 'on' ); ?> id="<?php echo 'sendmsgs_page_options_exc-cat-'. $cat->cat_ID; ?>" name="sendmsgs_page_options_exc[<?php echo 'cat-'. $cat->cat_ID; ?>]" />
                <label for="<?php echo 'sendmsgs_page_options_exc-cat-'. $cat->cat_ID; ?>"><?php echo $cat->cat_name ?></label></p>
            <?php
                    unset( $cat );
                }
            ?>
            </div>
            
            <h4 class="sendmsgs_toggle" style="cursor:pointer;margin-top:0;"><?php _e( 'Miscellaneous', 'sendmsgs' ) ?> </h4>
            <div class="sendmsgs_collapse">
                <ul>
            <?php

            foreach ( $wp_page_types as $key => $label ) {
                $instance_exclude['page-'. $key] = isset( $instance_exclude[ 'page-' . $key ] ) ? $instance_exclude[ 'page-' . $key ] : false;
                if($key == 'home'){
                    continue;
                }
            ?>
                    <li><input class="checkbox" type="checkbox" <?php checked( $instance_exclude[ 'page-' . $key ], 'on' ); ?> id="<?php echo 'sendmsgs_page_options_exc-page-'. $key; ?>" name="sendmsgs_page_options_exc[<?php echo 'page-'. $key; ?>]" />
                <label for="<?php echo 'sendmsgs_page_options_exc-page-'. $key; ?>"><?php echo $label; ?></label></li>
            <?php
            } ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div>&nbsp;</div>
    <p>
        <input type="radio" name="sendmsgs_include" id="sendmsgs_include_specific" value="specific" <?php echo checked( $sendmsgs_include, 'specific' ); ?> /> <label for="sendmsgs_include_specific"> <?php _e( 'Include in specific Pages', 'sendmsgs' ) ?></label>
    </p>
    <div class="sendmsgs-page-options  sendmsgs-page-options_inc_box" >
        <h4 class="sendmsgs_toggle" style="cursor:pointer;"><?php _e( 'Pages') ?> </h4>
        <div class="sendmsgs_collapse">
        <?php 
        foreach ( $pages as $page ) {
            $instance_include[ 'page-' . $page->ID ] = isset( $instance_include[ 'page-' . $page->ID ] ) ? $instance_include[ 'page-' . $page->ID ] : false;
        }

        // use custom Page Walker to build page list
        $args = array( 'instance' => $instance_include, 'option_type' => 'sendmsgs_page_options_inc' );
        $page_list_walked = $page_list->walk( $pages, 0, $args );
        if ( $page_list_walked ) {
            echo '<ul>' . $page_list_walked . '</ul>';
        }
        ?>
        </div>
          
        <h4 class="sendmsgs_toggle" style="cursor:pointer;"><?php _e( 'Categories') ?> </h4>
        <div class="sendmsgs_collapse">
            <?php $instance_include['cat-all'] = isset( $instance_include['cat-all'] ) ? $instance_include['cat-all'] : false; ?>
            <p><input class="checkbox" type="checkbox" <?php  checked( $instance_include['cat-all'], 'on' ); ?> id="sendmsgs_page_options_inc-cat-all" name="sendmsgs_page_options_inc[cat-all]" />
            <label for="sendmsgs_page_options_inc-cat-all"><?php _e( 'All Categories', 'sendmsgs' ); ?></label></p>
        <?php
            foreach ( $cats as $cat ) {
                $instance_include[ 'cat-' . $cat->cat_ID ] = isset( $instance_include[ 'cat-' . $cat->cat_ID ] ) ? $instance_include[ 'cat-' . $cat->cat_ID] : false;
        ?>
            <p><input class="checkbox" type="checkbox" <?php  checked( $instance_include['cat-'. $cat->cat_ID], 'on' ); ?> id="<?php echo 'sendmsgs_page_options_inc-cat-'. $cat->cat_ID; ?>" name="sendmsgs_page_options_inc[<?php echo 'cat-'. $cat->cat_ID; ?>]" />
            <label for="<?php echo 'sendmsgs_page_options_inc-cat-'. $cat->cat_ID; ?>"><?php echo $cat->cat_name ?></label></p>
        <?php
                unset( $cat );
            }
        ?>
        </div>
        
        <h4 class="sendmsgs_toggle" style="cursor:pointer;margin-top:0;"><?php _e( 'Miscellaneous', 'sendmsgs' ) ?> </h4>
        <div class="sendmsgs_collapse">
            <ul>
        <?php

        foreach ( $wp_page_types as $key => $label ) {
            $instance_include['page-'. $key] = isset( $instance_include[ 'page-' . $key ] ) ? $instance_include[ 'page-' . $key ] : false;
            if($key == 'home'){
                continue;
            }
        ?>
                <li><input class="checkbox" type="checkbox" <?php checked( $instance_include[ 'page-' . $key ], 'on' ); ?> id="<?php echo 'sendmsgs_page_options_inc-page-'. $key; ?>" name="sendmsgs_page_options_inc[<?php echo 'page-'. $key; ?>]" />
            <label for="<?php echo 'sendmsgs_page_options_inc-page-'. $key; ?>"><?php echo $label; ?></label></li>
        <?php
        } ?>
            </ul>
        </div>
    </div>
