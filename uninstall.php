<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); //prevent direct access

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-script-active' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-position' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_include' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_exclude' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_page_options_inc' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs_page_options_exc' );

unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-scripturl-1' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-scripturl-2' );
unregister_setting( 'sendmsgs-plugin-settings-group', 'sendmsgs-scripturl-3' );

delete_option('sendmsgs-script-active');
delete_option('sendmsgs-position');
delete_option('sendmsgs_include');
delete_option('sendmsgs_exclude');
delete_option('sendmsgs_page_options_inc');
delete_option('sendmsgs_page_options_exc');
delete_option('sendmsgs-scripturl-1');
delete_option('sendmsgs-scripturl-2');
delete_option('sendmsgs-scripturl-3');