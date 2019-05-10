<?php 

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

# Uninstall Comptoneye Appointment
if(isset($_POST['uninstallapcal'])) {

	if( !wp_verify_nonce($_POST['appointment_remove_nonce_check'],'appointment_remove_nonce_check') ){
			echo '<script>alert("Sorry, your nonce did not verify.");</script>';
			return false;
		}
	
	// Delete all plugin data
	comptoneye_delete_data();
	
	deactivate_plugins( plugin_basename( __FILE__ ) );
    ?>
    <div class="alert" style="width:95%; margin-top:10px;">
        <p><?php _e('Comptoneye Appointment Plugin has been successfully removed. It can be re-activated from the ', 'comptoneye'); ?> <strong><a href="plugins.php"><?php _e('Plugins Page', 'comptoneye'); ?></a></strong>.</p>
    </div>
    <?php
    return;
}

if(!isset($_POST['uninstallapcal'])) { ?>
<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
  <h3><?php _e('Remove Plugin Data', 'comptoneye'); ?></h3> 
</div>
<div class="alert alert-error" style="width:95%;">
    <form method="post">
	<?php wp_nonce_field('appointment_remove_nonce_check','appointment_remove_nonce_check'); ?>
    <h3><?php _e('Remove Comptoneye Appointment Plugin Data', 'comptoneye'); ?></h3>
    <p><?php _e('This operation wiil delete all Comptoneye Appointment data & settings. If you continue, You will not be able to retrieve or restore your appointments entries.', 'comptoneye'); ?></p>
	
	<p><?php _e('The plugin will also be deactivated after this operation.', 'comptoneye'); ?></p>
	
    <p><button id="uninstallapcal" type="submit" class="btn btn-danger" name="uninstallapcal" value="" onclick="return confirm('<?php _e('Warning! Comptoneye Appointment data & settings, including appointment entries will be deleted. This cannot be undone. OK to delete, CANCEL to stop', 'comptoneye'); ?>')" ><i class="icon-trash icon-white"></i> <?php _e('REMOVE PLUGIN DATA', 'comptoneye'); ?></button></p>
    </form>
</div>
<?php } ?>