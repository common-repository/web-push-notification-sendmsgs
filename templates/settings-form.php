<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); //prevent direct access
?>
<div class="wrap sendmsgs-container">
	<div class="sendmsgs-header">
		<img src="<?php echo  plugin_dir_url( __FILE__ ) .'../images/banner.jpg'; ?>" alt="SendMsgs" class="sendmsgs-banner">
	</div>
	<div class="sendmsgs-note">
		<p>Please visit <a href="http://www.sendmsgs.com" target="_blank">http://www.sendmsgs.com</a> to get your script URLs. For any support please contact to <a href="mailto:sales@sendmsgs.com?Subject=Inquiry%20Required">sales@sendmsgs.com</a></p>
	</div>
	<?php 
	global $success_message;
	global $error_messages;

	if($success_message!= '' ) { ?>
	<h2></h2>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e($success_message, 'sendmsgs');?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
	<?php } ?>
	<?php if(!empty($error_messages) && isset($error_messages['sendmsgs-main'])) { ?>
	<h2></h2>
	<div class="notice notice-error is-dismissible"> 
		<p><strong><?php _e($error_messages['sendmsgs-main'], 'sendmsgs');?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
	<?php } ?>
	<form method="post" action="" id="sendmsgs-form" name="sendmsgs-form">
		<?php wp_nonce_field( 'sendmsgs_admin'); ?>
	    
	    <table class="form-table">
	        <tr>
	       		<th scope="row"><label for="blogname">Include scripts :</label></td>
	       		<td>
	       			<label class="switch switch-left-right">
		        		<input class="switch-input" name="sendmsgs-script-active" id="sendmsgs-script-active" type="checkbox" value="active" <?php if(get_option( 'sendmsgs-script-active', 'active') == 'active') echo 'checked="checked"';?> />	
		        		<span class="switch-label" data-on="On" data-off="Off"></span> 
		        		<span class="switch-handle"></span> 
	    			</label>
	    		</td>
	        </tr>
	        <tr>
	        	<th>Script Position : </th>
	        	<td >
	       			<?php   $options = get_option( 'sendmsgs-position','head' ); ?>
	                <input type="radio" name="sendmsgs-position" id="sendmsgs-position-head" value="head" <?php if( 'head' == $options ) echo 'checked="checked"'; ?> /> <label for="sendmsgs-position-head"><strong>Add script in head</strong></label>
	                <p class="description" id="sendmsgs-position-1-description">The script will be added before closing head tag i.e. &lt;/head&gt;</p>
	                <br>
	                <input type="radio" name="sendmsgs-position" id="sendmsgs-position-body" value="footer" <?php if( 'footer' == $options ) echo 'checked="checked"'; ?> /> <label for="sendmsgs-position-body"><strong>Add script in footer</strong></label>
	                <p class="description" id="sendmsgs-position-2-description">The script will be added before closing body tag i.e. &lt;/body&gt;</p>
	            </td>
	        </tr>
	        <tr>
	      		<th scope="row"><label for="sendmsgs-scripturl-1">Script #1 <span class="description">(optional)</span> :</label></th>
	        	<td>
	        		<input type="text" class="regular-text" name="sendmsgs-scripturl-1" id="sendmsgs-scripturl-1" value="<?php echo esc_url( get_option('sendmsgs-scripturl-1') ); ?>" placeholder="e.g. https://www.example.com/js/your-script.js" />
	        		<p class="description">Leave blank if JQuery already included.</p>
	        	</td>
	        </tr>
	        <tr>
	        	<th scope="row"><label for="sendmsgs-scripturl-2">Script #2 <span class="description">(required)</span> :</label></th>
	        	<td>
		        	<input type="text"  class="regular-text" aria-required="true" name="sendmsgs-scripturl-2" id="sendmsgs-scripturl-2" value="<?php echo esc_url( get_option('sendmsgs-scripturl-2') ); ?>"  placeholder="e.g. https://www.example.com/js/your-script.js"/>
		        	<a href="http://www.sendmsgs.com" target="_blank"><span class="sendmsgs-icon sendmsgs-icon-question"></span></a>
		        	<a href="mailto:sales@sendmsgs.com?Subject=Inquiry%20Required"><span class="sendmsgs-icon sendmsgs-icon-email"></span></a>
		        	<span class="error"><?php if(!empty($error_messages) && isset($error_messages['sendmsgs-scripturl-2'])) {  _e($error_messages['sendmsgs-scripturl-2']); } ?></span>
	        	</td>
	        </tr>        
	        
	        <tr>
	        	<th scope="row"><label for="sendmsgs-scripturl-3">Script #3 <span class="description">(required)</span> :</label></th>
	        	<td>
	        		<input type="text" class="regular-text" aria-required="true" name="sendmsgs-scripturl-3" id="sendmsgs-scripturl-3" value="<?php echo esc_url( get_option('sendmsgs-scripturl-3') ); ?>"  placeholder="e.g. https://www.example.com/js/your-script.js" />
	        		<a href="http://www.sendmsgs.com" target="_blank"><span class="sendmsgs-icon sendmsgs-icon-question"></span></a>
		        	<a href="mailto:sales@sendmsgs.com?Subject=Inquiry%20Required"><span class="sendmsgs-icon sendmsgs-icon-email"></span></a>
		        	<span class="error"><?php if(!empty($error_messages) && isset($error_messages['sendmsgs-scripturl-3'])) {  _e($error_messages['sendmsgs-scripturl-3']); } ?></span>
	        	</td>
	        </tr>
	        <tr>
	        	<th><label for="sendmsgs_include">Page Options <span class="description">(optional)</span> :</label></th>
	        	<td>
	        		<?php show_hide_widget_options( );?>
	        	</td>
	        </tr>
	    </table>

	    <?php 
	    	if(function_exists('submit_button')){
	    		submit_button(); 
	    	} else { 
	    ?>
	    	<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
	    <?php
	    	}
	    ?>
	</form>
</div>
