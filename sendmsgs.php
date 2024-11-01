<?php
/*
Plugin Name: SendMsgs - Web Push Notifications
Plugin URI: https://www.sendmsgs.com/
Description: Push Your Greetings towards your valuable customers without any Hassle
Version: 1.0.0
Author: SendMsgs
Author URI: https://www.sendmsgs.com/
Text Domain: sendmsgs
Domain Path: /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); //prevent direct access

/**
*   Add custom style and script
*/
function sendmsgs_enqueued_assets() {
    wp_enqueue_style( 'sendmsgs-admin-style', plugin_dir_url( __FILE__ ) .'css/admin-style.css' );
    wp_enqueue_script( 'sendmsgs-script', plugin_dir_url( __FILE__ ) . 'js/admin-script.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'sendmsgs_enqueued_assets' );

/**
*  Add plugin menu in admin
*/
function sendmsgs_plugin_menu() {
    add_menu_page('SendMsgs', 'SendMsgs Settings', 'administrator', 'sendmsgs-settings', 'sendmsgs_plugin_settings_page', 'dashicons-admin-generic');
}
add_action('admin_menu', 'sendmsgs_plugin_menu');

function sendmsgs_plugin_settings_page() {
    plugin_dir_url( __FILE__ ) .'images/logo.png';
    $success_message = '';
    $error_messages = array();
    sendmsgs_save_settings();
    load_template( dirname( __FILE__ ) . '/templates/settings-form.php' );
}

/**
*   add script in head or footer
*/
function sendmsgs_add_script()
{
    $showScripts = sendmsgs_check_add_script();
    if($showScripts)
    {
        $in_footer = true;
        $script_position = get_option( 'sendmsgs-position', 'head');
        $script_url_1 = get_option('sendmsgs-scripturl-1' , '');
        $script_url_2 = get_option('sendmsgs-scripturl-2' , '');
        $script_url_3 = get_option('sendmsgs-scripturl-3' , '');

        if($script_position == 'head')
        {
            $in_footer = false;
        }

        if($script_url_1 != ''){
            wp_enqueue_script( 'sendmsgs-script-1', esc_url($script_url_1), array( 'jquery' ), '1.0', $in_footer );
        }

        if($script_url_2 != ''){
            wp_enqueue_script( 'sendmsgs-script-2', esc_url($script_url_2), array( 'jquery' ), '1.0', $in_footer );
        }

        if($script_url_3 != ''){
            wp_enqueue_script( 'sendmsgs-script-3', esc_url($script_url_3), array( 'jquery' ), '1.0', $in_footer );
        }
    }
}//end fn sendmsgs_add_script

/**
*   Check plugin page options
*/
function sendmsgs_check_add_script()
{
    if(function_exists('get_queried_object_id'))
    {
        $post_id = get_queried_object_id();
    }
    else
    {
        global $post;
        $post_id = $post->ID;
    }

    $instance_include = unserialize(get_option('sendmsgs_page_options_inc',serialize(array())));
    $instance_exclude = unserialize(get_option('sendmsgs_page_options_exc',serialize(array())));

    $is_plug_active = get_option( 'sendmsgs-script-active', 'active');
    $sendmsgs_include = get_option( 'sendmsgs_include', 'all');
    $sendmsgs_exclude = get_option( 'sendmsgs_exclude', 0);
    $page_show = false;

    if($is_plug_active != 'active')
    {
        return false;
    }
    else if($sendmsgs_include == 'all')
    {
        $show = true;   
        //Check exlcude list
        if($sendmsgs_exclude)
        {
            if ( is_home() )
            {
                $page_show = isset( $instance_exclude['page-home'] ) ? $instance_exclude['page-home'] : false;
                
                if ( ! $page_show && $post_id ) {
                    $page_show = isset( $instance_exclude[ 'page-' . $post_id ] ) ? $instance_exclude[ 'page-' . $post_id ] : false;
                }
                // check if blog page is front page too
                if ( ! $page_show && is_front_page() && isset( $instance_exclude['page-front'] ) ) {
                    $page_show = $instance_exclude['page-front'];
                }

            } else if ( is_front_page() ) {
                $page_show = isset( $instance_exclude['page-front'] ) ? $instance_exclude['page-front'] : false;
                if ( ! $page_show && $post_id ) {
                    $page_show = isset( $instance_exclude[ 'page-' . $post_id ] ) ? $instance_exclude[ 'page-' . $post_id ] : false;
                }
            } else if ( is_category() ) {
                $page_show = isset( $instance_exclude['cat-all'] ) ? $instance_exclude['cat-all'] : false;

                if ( ! $page_show ) {
                    $page_show = isset( $instance_exclude['cat-' . get_query_var('cat') ] ) ? $instance_exclude[ 'cat-' . get_query_var('cat') ] : false;
                }
            } else if ( is_tax() ) {
                $term = get_queried_object();
                $page_show = isset( $instance_exclude[ 'tax-' . $term->taxonomy ] ) ? $instance_exclude[ 'tax-'. $term->taxonomy] : false;
                unset( $term );
            } else if ( function_exists('is_post_type_archive') && is_post_type_archive() ) {
                $type = get_post_type();
                $page_show = isset( $instance_exclude[ 'type-' . $type . '-archive' ] ) ? $instance_exclude[ 'type-' . $type . '-archive' ] : false;
            } else if ( is_archive() ) {
                $page_show = isset( $instance_exclude['page-archive'] ) ? $instance_exclude['page-archive'] : false;
            } else if ( is_single() ) {
                $type = get_post_type();
                if ( $type != 'page' && $type != 'post' ) {
                    $page_show = isset( $instance_exclude[ 'type-' . $type ] ) ? $instance_exclude[ 'type-' . $type ] : false;
                }
                if ( !  $page_show  ) {
                    $page_show = isset( $instance_exclude['page-single'] ) ? $instance_exclude['page-single'] : false;
                }
                
                if ( !  $page_show  ) {
                    $page_show = isset( $instance_exclude['cat-all'] ) ? $instance_exclude['cat-all'] : false;
                }

                if ( ! $page_show ) {
                    $cats = get_the_category();
                    foreach ( $cats as $cat ) {
                        $page_show = isset( $instance_exclude['cat-' . $cat->cat_ID ] ) ? $instance_exclude[ 'cat-' . $cat->cat_ID ] : false;
                        if ( $page_show ) {
                            break;
                        }
                        
                        unset( $c_id, $cat );
                    }
                }
            } else if ( is_404() ) {
                $page_show = isset( $instance_exclude['page-404'] ) ? $instance_exclude['page-404'] : false;
            } else if ( is_search() ) {
                $page_show = isset( $instance_exclude['page-search'] ) ? $instance_exclude['page-search'] : false;
            } else if ( $post_id ) {
                $page_show = isset( $instance_exclude[ 'page-' . $post_id ] ) ? $instance_exclude[ 'page-' . $post_id ] : false;
            } else {
                $page_show = false;
            }

            //invert the show flag if page selected
            if($page_show)
            {
                $show = false;
            }
        }//exlcude list
    }
    if($sendmsgs_include == 'specific')
    {
        //check include list.
        if ( is_home() )
        {
            $page_show = isset( $instance_include['page-home'] ) ? $instance_include['page-home'] : false;
            
            if ( ! $page_show && $post_id ) {
                $page_show = isset( $instance_include[ 'page-' . $post_id ] ) ? $instance_include[ 'page-' . $post_id ] : false;
            }
            // check if blog page is front page too
            if ( ! $page_show && is_front_page() && isset( $instance_include['page-front'] ) ) {
                $page_show = $instance_include['page-front'];
            }

        } else if ( is_front_page() ) {
            $page_show = isset( $instance_include['page-front'] ) ? $instance_include['page-front'] : false;
            if ( ! $page_show && $post_id ) {
                $page_show = isset( $instance_include[ 'page-' . $post_id ] ) ? $instance_include[ 'page-' . $post_id ] : false;
            }
        } else if ( is_category() ) {
            $page_show = isset( $instance_include['cat-all'] ) ? $instance_include['cat-all'] : false;

            if ( ! $page_show ) {
                $page_show = isset( $instance_include['cat-' . get_query_var('cat') ] ) ? $instance_include[ 'cat-' . get_query_var('cat') ] : false;
            }
        } else if ( is_tax() ) {
            $term = get_queried_object();
            $page_show = isset( $instance_include[ 'tax-' . $term->taxonomy ] ) ? $instance_include[ 'tax-'. $term->taxonomy] : false;
            unset( $term );
        } else if ( function_exists('is_post_type_archive') && is_post_type_archive() ) {
            $type = get_post_type();
            $page_show = isset( $instance_include[ 'type-' . $type . '-archive' ] ) ? $instance_include[ 'type-' . $type . '-archive' ] : false;
        } else if ( is_archive() ) {
            $page_show = isset( $instance_include['page-archive'] ) ? $instance_include['page-archive'] : false;
        } else if ( is_single() ) {
            $type = get_post_type();
            if ( $type != 'page' && $type != 'post' ) {
                $page_show = isset( $instance_include[ 'type-' . $type ] ) ? $instance_include[ 'type-' . $type ] : false;
            }
            if ( !  $page_show ) {
                $page_show = isset( $instance_include['page-single'] ) ? $instance_include['page-single'] : false;
            }

            if ( !  $page_show  ) {
                $page_show = isset( $instance_exclude['cat-all'] ) ? $instance_exclude['cat-all'] : false;
            }

            if ( ! $page_show ) {
                $cats = get_the_category();
                foreach ( $cats as $cat ) {
                    $page_show = isset( $instance_exclude['cat-' . $cat->cat_ID ] ) ? $instance_exclude[ 'cat-' . $cat->cat_ID ] : false;
                    if ( $page_show ) {
                        break;
                    }
                    
                    unset( $c_id, $cat );
                }
            }
        } else if ( is_404() ) {
            $page_show = isset( $instance_include['page-404'] ) ? $instance_include['page-404'] : false;
        } else if ( is_search() ) {
            $page_show = isset( $instance_include['page-search'] ) ? $instance_include['page-search'] : false;
        } else if ( $post_id ) {
            $page_show = isset( $instance_include[ 'page-' . $post_id ] ) ? $instance_include[ 'page-' . $post_id ] : false;
        } else {
            $page_show = false;
        }

        //include script if page is listed.
        if($page_show)
        {
            $show = true;
        }
        else
        {
            $show = false;
        }
    }//endif 

    if ( ! isset( $show ) ) {
        $show = false;
    }

    return $show;
}//end sendmsgs_check_add_script

if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'sendmsgs_add_script', 99999);
}

/**
*   Add plugin settings 
*/
function sendmsgs_save_settings()
{
    global $success_message;
    global $error_messages;
    
    if ( ! empty( $_POST ))
    {
        //Validate form submission.
        check_admin_referer( 'sendmsgs_admin');

        if(isset($_POST['sendmsgs-script-active']))
        {
            update_option( 'sendmsgs-script-active', sanitize_text_field($_POST['sendmsgs-script-active']));
        }
        else
        {
            update_option( 'sendmsgs-script-active', '');
        }

        if(isset($_POST['sendmsgs-position']))
        {
            update_option( 'sendmsgs-position', sanitize_text_field($_POST['sendmsgs-position']));
        }

        if(isset($_POST['sendmsgs_include']))
        {
            update_option( 'sendmsgs_include', sanitize_text_field($_POST['sendmsgs_include']));
        }

        if(isset($_POST['sendmsgs_exclude']))
        {
            update_option( 'sendmsgs_exclude', intval($_POST['sendmsgs_exclude']));
        }
        else
        {
            update_option( 'sendmsgs_exclude', 0);
        }

        /*Store Page Array*/
        if(isset($_POST['sendmsgs_page_options_inc']))
        {
            if(is_array($_POST['sendmsgs_page_options_inc']))
            {
                $page_options = sanitize_text_field(serialize($_POST['sendmsgs_page_options_inc']));
            }
            else
            {
                $page_options = serialize(array());
            }
            update_option( 'sendmsgs_page_options_inc', $page_options);
        }
        else
        {
            update_option( 'sendmsgs_page_options_inc', serialize(array()));
        }

        if(isset($_POST['sendmsgs_page_options_exc']))
        {
            if(is_array($_POST['sendmsgs_page_options_exc']))
            {
                $page_options = sanitize_text_field(serialize($_POST['sendmsgs_page_options_exc']));
            }
            else
            {
                $page_options = serialize(array());
            }
            update_option( 'sendmsgs_page_options_exc', $page_options);
        }
        else
        {
            update_option( 'sendmsgs_page_options_exc', serialize(array()));
        }
        
        if(isset($_POST['sendmsgs-scripturl-1']))
        {
            update_option( 'sendmsgs-scripturl-1', esc_url_raw(sanitize_text_field($_POST['sendmsgs-scripturl-1'])));
        }

        if(isset($_POST['sendmsgs-scripturl-2']))
        {
            update_option( 'sendmsgs-scripturl-2', esc_url_raw(sanitize_text_field($_POST['sendmsgs-scripturl-2'])));
        }
        else
        {
            $error_messages['sendmsgs-scripturl-2'] = __('Pleaes enter a url');
        }

        if(isset($_POST['sendmsgs-scripturl-3']))
        {
            update_option( 'sendmsgs-scripturl-3', esc_url_raw(sanitize_text_field($_POST['sendmsgs-scripturl-3'])));
        }
        else
        {
            $error_messages['sendmsgs-scripturl-3'] = __('Pleaes enter a url');
        }
        if(empty($error_messages))
        {
            $success_message = __('Settings have been saved!');
        }
        else
        {
            $error_messages['sendmsgs-main'] = __('Please clear following errors and then save again.');
        }
    }
}
 //add_action( 'admin_notices', 'sendmsgs_show_success' );
/**
*   Add plugin settings 
*/
function sendmsgs_plugin_settings() {
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-script-active' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-position' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_include' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_page_options_inc' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_page_options_exc' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_exclude' );
    
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-scripturl-1' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-scripturl-2' );
    register_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-scripturl-3' );
}
add_action( 'admin_init', 'sendmsgs_plugin_settings' );


function page_types(){
    $page_types = array(
        'front'     => __( 'Front', 'display-widgets' ),
        'home'      => __( 'Blog', 'display-widgets' ),
        'archive'   => __( 'Archives'),
        'single'    => __( 'Single Post'),
        '404'       => '404',
        'search'    => __( 'Search'),
    );
    
    return apply_filters('sendmsgs_pages_types_register', $page_types);
}

function show_hide_widget_options( ) {
    load_template( dirname( __FILE__ ) . '/templates/page-options.php' );    
}

function sendmsgs_show_success() {
    ?>
    <div class="updated notice">
        <p><?php _e( 'The settings has been saved.', 'sendmsgs' ); ?></p>
    </div>
    <?php
}
/*
custom Page Walker class
*/
class sendmsgs_Walker_Page_List extends Walker_Page {

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "\n<ul class='children'>\n";
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "</ul>\n";
    }

    function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
        if ( $depth )
            $indent = str_repeat("&mdash; ", $depth);
        else
            $indent = '';

        // args: $instance, $widget
        extract( $args, EXTR_SKIP );
    

        if ( '' === $page->post_title ) {
            $page->post_title = sprintf( __( '#%d (no title)', 'display-widgets' ), $page->ID );
        }

        $output .= '<li>' . $indent;
        $output .= '<input class="checkbox" type="checkbox" ' . checked( $instance[ 'page-' . $page->ID ], 'on', false ) . ' id="' .$option_type. '-page-'. $page->ID . '" name="'.$option_type.'[' . 'page-'. $page->ID.']"  />';

        $output .= '<label for="' .$option_type. '-page-'. $page->ID . '">' . apply_filters( 'the_title', $page->post_title, $page->ID ) . '</label>';
    }

    function end_el( &$output, $page, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}