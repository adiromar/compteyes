
<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
?>
<div class="bs-docs-example tooltip-demo" style="background-color: #FFFFFF;">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><?php _e("Services", "comptoneye"); ?></h3></div>
    <?php
    global $wpdb;
    //get all category list
    $ServiceCategoryTable = $wpdb->prefix . "ap_service_category";
    $ServiceCategory = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$ServiceCategoryTable` where id > %d",null));
    foreach($ServiceCategory as $GroupName) { ?>
        <table class="table">
            <thead>
                <tr style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
                    <th colspan="3">
                        <div id="gruopnamedivbox<?php echo $GroupName->id; ?>"><?php echo ucfirst($GroupName->name); ?></div>
                        <div id="gruopnameedit<?php echo $GroupName->id; ?>" style="display:none; height:25px;">
                            <form method="post">
                                <input type="text" id="editgruopname" class="inputheight" name="editgruopname" value="<?php echo esc_attr($GroupName->name); ?>"/>
                                <button id="editgruop" value="<?php echo esc_attr($GroupName->id); ?>" name="editgruop" type="submit" class="btn btn-small btn-success"><i class="icon-ok icon-white"></i> <?php _e("Save", "comptoneye"); ?></button>
                                <button id="editgruopcancel" type="button" class="btn btn-small btn-danger" onclick="canceleditgrup('<?php echo $GroupName->id; ?>')"><i class="icon-remove icon-white"></i> <?php _e("Cancel", "comptoneye"); ?></button>
                            </form>
                        </div>
                    </th>
                    <th id="yw7_c1" colspan="3">
                        <!--- header rename and delete button right box-->
                        <div align="right">
                            <?php if($GroupName->id =='1') ?>
                                <a rel="tooltip" href="#" data-placement="left" class="btn btn-success btn-small" id="<?php echo $GroupName->id; ?>" onclick="editgruop('<?php echo $GroupName->id; ?>')" title="<?php _e("Rename Category", "comptoneye"); ?>"><?php _e("Rename", "comptoneye"); ?></a>
                            <?php if($GroupName->id !='1') { ?>
                                | <a rel="tooltip" href="?page=service&gid=<?php echo $GroupName->id; ?>" class="btn btn-danger btn-small" onclick="return confirm('<?php _e("Do you want to delete this Category?", "comptoneye"); ?>')" title="<?php _e("Delete", "comptoneye"); ?>"><?php _e("Delete", "comptoneye"); ?></a>
                            <?php } ?>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th><strong><?php _e("Name", "comptoneye"); ?></strong></th>
                    <th><strong><?php _e("Description", "comptoneye"); ?></strong></th>
                    <th><strong><?php _e("Duration", "comptoneye"); ?></strong></th>
                    <th><strong><?php _e("Cost", "comptoneye"); ?></strong></th>
                    <th><strong><?php _e("Availability", "comptoneye"); ?></strong></th>
                    <th><strong><?php _e("Action", "comptoneye"); ?></strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // get service list group wise
                $ServiceTable = $wpdb->prefix . "ap_services";
                $ServiceDetails = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$ServiceTable` WHERE `category_id` = %s ",$GroupName->id));
                foreach($ServiceDetails as $Service) { ?>
                <tr class="odd" style="border-bottom:1px;">
                    <td><em><?php echo ucwords($Service->name); ?></em></td>
                    <td> <em><?php echo ucfirst($Service->desc); ?></em> </td>
                    <td><em><?php echo $Service->duration. " ".ucfirst($Service->unit); ?></em></td>
                    <td><em><?php echo '&#36;'.$Service->cost; ?></em></td>
                    <td><em><?php echo ucfirst($Service->availability); ?></em></td>
                    <td class="button-column">
                        <a rel="tooltip" href="?page=manage-service&sid=<?php echo $Service->id; ?>" title="<?php _e("Update", "comptoneye"); ?>"><i class="icon-pencil"></i></a> &nbsp;
                        <?php if($Service->id != 1) { ?>
                        <a rel="tooltip" href="?page=service&sid=<?php echo $Service->id; ?>" onclick="return confirm('<?php echo _e("Do you want to delete this service?", "comptoneye"); ?>')" title="<?php _e("Delete", "comptoneye"); ?>" ><i class="icon-remove"></i>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="6">
                        <a href="?page=manage-service&gid=<?php echo $GroupName->id; ?>" rel="tooltip" title="<?php _e("Add New Service to this Category", "comptoneye"); ?>"><?php _e("+ Add New Service to this Category", "comptoneye"); ?></a>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php  } ?>
    <!---New category div box--->
    <div id="gruopbuttonbox">
        <a class="btn btn-info" href="#" rel="tooltip" class="Create Gruop" onclick="creategruopname()"><i class="icon-plus icon-white"></i> <?php _e("Create New Service Category", "comptoneye"); ?></a></u>
    </div>

    <div style="display:none;" id="gruopnamebox">
        <form method="post">
			<?php wp_nonce_field('appointment_add_cat_nonce_check','appointment_add_cat_nonce_check'); ?>
            <?php _e("Service Category name ", "comptoneye"); ?>: <input type="text" id="gruopname" name="gruopname" class="inputheight" />
            <button style="margin-bottom:10px;" id="CreateGruop" type="submit" class="btn btn-small btn-success" name="CreateGruop"><i class="icon-ok icon-white"></i> <?php _e("Create Category", "comptoneye"); ?></button>
            <button style="margin-bottom:10px;" id="CancelGruop" type="button" class="btn btn-small btn-danger" name="CancelGruop" onclick="cancelgrup();"><i class="icon-remove icon-white"></i> <?php _e("Cancel", "comptoneye"); ?></button>
        </form>
    </div>
    <!---New category div box end --->


    <?php // insert new service category
    $ServiceCategoryTable = $wpdb->prefix . "ap_service_category";
    $ServiceTable = $wpdb->prefix . "ap_services";
    if(isset($_POST['CreateGruop'])) {
		
		if( !wp_verify_nonce($_POST['appointment_add_cat_nonce_check'],'appointment_add_cat_nonce_check') ){
			echo '<script>alert("Sorry, your nonce did not verify.");</script>';
			return false;
		}
		
        global $wpdb;
        $groupename = sanitize_text_field( $_POST['gruopname'] );
        $wpdb->query($wpdb->prepare("INSERT INTO `$ServiceCategoryTable` ( `name` ) VALUES (%s);",$groupename));
        echo "<script>alert('" . __('Service category successfully created.', 'comptoneye') ."')</script>";
        echo "<script>location.href='?page=service';</script>";
    }

    // update service category
    if(isset($_POST['editgruop'])) {
        $update_id = intval( $_POST['editgruop'] );
        $update_name = sanitize_text_field( $_POST['editgruopname'] );
        $tt = !is_numeric($update_name);
        if($update_name) {
            if(!is_numeric($update_name)) {
                $wpdb->query($wpdb->prepare("UPDATE `$ServiceCategoryTable` SET `name` = '$update_name' WHERE `id` =%s;",$update_id));
                echo "<script>location.href='?page=service';</script>";
            } else {
            echo "<script>alert('". __("Invalid category name.", "comptoneye") ."');</script>";
            }
        } else {
            echo "<script>alert('". __("Category name cannot be blank.", "comptoneye") ."');</script>";
        }
    }

    // Delete service category
    if(isset($_GET['gid'])) {
        $DeleteId = intval( $_GET['gid'] );
        $wpdb->query($wpdb->prepare("DELETE FROM `$ServiceCategoryTable` WHERE `id` = %s;",$DeleteId));

        //update all service category id
        $UpdateServiceSQL = "UPDATE `$ServiceTable` SET `category_id` = '1' WHERE `category_id` ='$DeleteId';";
        $wpdb->query($UpdateServiceSQL); // update category
        echo "<script>alert('" . __('Service category successfully deleted.', 'comptoneye') ."')</script>";
        echo "<script>location.href='?page=service';</script>";
    }

    // Delete service
    if(isset($_GET['sid'])) {
        $DeleteId = intval( $_GET['sid'] );
        $wpdb->query($wpdb->prepare("DELETE FROM `$ServiceTable` WHERE `id` = %s;",$DeleteId));
        echo "<script>alert('" . __('Service successfully delete.', 'comptoneye') ."')</script>";
        echo "<script>location.href='?page=service';</script>";
    }
?>
</div>
<!--end of tooltip div-->

<!--js work-->
<style type="text/css">
    .error {  color:#FF0000;
    }
    input.inputheight {
        height:30px;
    }

    #editgruop {
        margin-bottom:10px;
    }

    #editgruopcancel {
        margin-bottom:10px;
    }
</style>

<script type="text/javascript">
    // edit group hide and show div box
    function editgruop(id) {
        var gneb='#gruopnamedivbox'+id;
        var gne='#gruopnameedit'+id;
        jQuery(gneb).hide();
        jQuery(gne).show();
    }

    function canceleditgrup(id) {
        var gneb='#gruopnamedivbox'+id;
        var gne='#gruopnameedit'+id;
        jQuery(gneb).show();
        jQuery(gne).hide();
    }

    //group create and  hide  or show div box ajax post data
    function creategruopname() {
        jQuery('#gruopnamebox').show();
        jQuery('#gruopbuttonbox').hide();
    }

    function cancelgrup() {
        jQuery('#gruopnamebox').hide();
        jQuery('#gruopbuttonbox').show();
    }

    jQuery(document).ready(function () {
        // create new group js
        jQuery('#CreateGruop').click(function() {
            jQuery('.error').hide();
            var gruopname = jQuery("input#gruopname").val();
            if (gruopname == "") {
                jQuery("#CancelGruop").after('<span class="error">&nbsp;<br><strong><?php _e('Category name cannot be blank.', 'comptoneye'); ?></strong></span>');
                return false;
            } else {
                var gruopname = isNaN(gruopname);
                if(gruopname == false) {
                    jQuery("#CancelGruop").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid category name.', 'comptoneye'); ?></strong></span>');
                    return false;
                }
            }
            jQuery('#gruopnamebox').hide();
            jQuery('#gruopbuttonbox').show();
        });
    });
</script>