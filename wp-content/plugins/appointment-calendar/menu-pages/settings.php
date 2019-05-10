<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
        
?>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<script src= "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><?php _e("Settings", "comptoneye"); ?></h3></div>
   <div id="savedinfo"> </div>
    <div class="bs-docs-example" style="background-color: #FFFFFF;">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a class="tabmenu"  data-toggle="tab" href="#calendar-settings"><?php _e("Calendar Settings", "comptoneye"); ?></a></li>
            <li><a class="tabmenu" data-toggle="tab" href="#notification-settings"><?php _e("Notification Settings", "comptoneye"); ?></a></li>
            <li><a class="tabmenu" data-toggle="tab" href="#notification-message"><?php _e("Notification Message", "comptoneye"); ?></a></li>
        </ul>

        <!--tabs-body-->
        <div class="tab-content" id="myTabContent" style="padding-left: 15px;">

            <!--calendar settings-->
            <div id="calendar-settings" class="tab-pane fade in active">
                <?php $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings')); ?>
                <fieldset>
                    <legend><?php _e("Manage Calendar Settings", "comptoneye"); ?></legend>
                    <?php wp_nonce_field('appointment_cal_nonce_check','appointment_cal_nonce_check'); ?>
                    <table width="100%" class="table">
                        <tr>
                            <th width="18%" align="right" scope="row"><?php _e("Calendar Slot Time", "comptoneye"); ?></th>
                            <td width="3%" align="center"><strong>:</strong></td>
                            <td width="79%">
                                <?php $CalendarSlotTime = $AllCalendarSettings['calendar_slot_time']; ?>
                                <select name="calendar_slot_time" id="calendar_slot_time">
                                    <option value="15" <?php if($CalendarSlotTime && $CalendarSlotTime == '15') echo "selected"; ?>><?php _e("15 Minute", "comptoneye"); ?></option>
                                    <option value="30" <?php if($CalendarSlotTime && $CalendarSlotTime == '30') echo "selected"; ?>><?php _e("30 Minute", "comptoneye"); ?></option>
                                    <option value="60" <?php if($CalendarSlotTime && $CalendarSlotTime == '60') echo "selected"; ?>><?php _e("60 Minute", "comptoneye"); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Calendar Time Slot", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Day Start Time", "comptoneye"); ?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <?php $day_start_time = $AllCalendarSettings['day_start_time']; ?>
                                <select name="day_start_time" id="day_start_time">
                                    <?php
                                    $biz_start_time = strtotime("01:00 AM");
                                    $biz_end_time = strtotime("11:00 PM");
                                    //making 15min slots
                                    for( $i = $biz_start_time; $i <= $biz_end_time; $i += (60*(15))) {
                                        if( $day_start_time && $day_start_time == date('g:i A', $i) ) {
                                            $selected = 'selected';
                                        } else {
                                            $selected='';
                                        }
                                        echo "<option $selected value='". date('g:i A', $i)."'>". date('g:i A', $i) ."</option>";
                                    }
                                    ?>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Calendar Day Start Time", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Day End Time", "comptoneye"); ?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <?php $day_end_time = $AllCalendarSettings['day_end_time']; ?>
                                <select name="day_end_time" id="day_end_time">
                                    <?php
                                    //making 60min slots
                                    for( $i = $biz_start_time; $i <= $biz_end_time; $i += (60*(15))) {
                                        if( $day_end_time && $day_end_time == date('g:i A', $i) ) {
                                            $selected = 'selected';
                                        } else {
                                            $selected='';
                                        }
                                        echo "<option $selected value='". date('g:i A', $i)."'>". date('g:i A', $i) ."</option>";
                                    }
                                    ?>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Calendar Day End Time", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Calendar View", "comptoneye"); ?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <?php
                                $CalendarView = $AllCalendarSettings['calendar_view']; ?>
                                <select id="calendar_view" name="calendar_view">
                                    <option value="agendaDay" <?php if($CalendarView && $CalendarView == 'agendaDay') echo "selected"; ?>><?php _e("Day", "comptoneye"); ?></option>
                                    <option value="agendaWeek" <?php if($CalendarView && $CalendarView == 'agendaWeek') echo "selected"; ?>><?php _e("Week", "comptoneye"); ?></option>
                                    <option value="month" <?php if($CalendarView && $CalendarView == 'month') echo "selected"; ?>><?php _e("Month", "comptoneye"); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Calendar View", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Calendar First Day", "comptoneye"); ?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <?php $CalendarStartDay = $AllCalendarSettings['calendar_start_day']; ?>
                                <select name="calendar_start_day" id="calendar_start_day">
                                    <option value="1" <?php if($CalendarStartDay == 1) echo "selected";  ?>><?php _e("Monday", "comptoneye"); ?></option>
                                    <option value="2" <?php if($CalendarStartDay == 2) echo "selected";  ?>><?php _e("Tuesday", "comptoneye"); ?></option>
                                    <option value="3" <?php if($CalendarStartDay == 3) echo "selected";  ?>><?php _e("Wednesday", "comptoneye"); ?></option>
                                    <option value="4" <?php if($CalendarStartDay == 4) echo "selected";  ?>><?php _e("Thursday", "comptoneye"); ?></option>
                                    <option value="5" <?php if($CalendarStartDay == 5) echo "selected";  ?>><?php _e("Friday", "comptoneye"); ?></option>
                                    <option value="6" <?php if($CalendarStartDay == 6) echo "selected";  ?>><?php _e("Saturday", "comptoneye"); ?></option>
                                    <option value="0" <?php if($CalendarStartDay == 0) echo "selected";  ?>><?php _e("Sunday", "comptoneye"); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Calendar First Day", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Booking Button Text", "comptoneye")?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <input name="booking_button_text" style="width:220px;" type="text" id="booking_button_text" value="<?php echo esc_attr($AllCalendarSettings['booking_button_text']);?>" />
                                &nbsp;<a href="#" rel="tooltip" title="<?php _e("Booking Button Text", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Booking Time Slot", 'comptoneye'); ?></th> <td align="center"><strong>:</strong></td>
                            <td><?php if(isset($AllCalendarSettings['booking_time_slot'])) {
                                    $BookingTimeSlot = $AllCalendarSettings['booking_time_slot'];
                                } else {
                                    $BookingTimeSlot = 30;
                                } ?>
                                <select name="booking_time_slot" id="booking_time_slot">
                                    <option <?php if($BookingTimeSlot == 5) echo "selected"; ?> value="5"><?php _e("5 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 10) echo "selected"; ?> value="10"><?php _e("10 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 15) echo "selected"; ?> value="15"><?php _e("15 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 20) echo "selected"; ?> value="20"><?php _e("20 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 25) echo "selected"; ?> value="25"><?php _e("25 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 30) echo "selected"; ?> value="30"><?php _e("30 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 35) echo "selected"; ?> value="35"><?php _e("35 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 40) echo "selected"; ?> value="40"><?php _e("40 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 45) echo "selected"; ?> value="45"><?php _e("45 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 60) echo "selected"; ?> value="60"><?php _e("60 Minutes (1 Hour)", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 75) echo "selected"; ?> value="75"><?php _e("75 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 90) echo "selected"; ?> value="90"><?php _e("90 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 120) echo "selected"; ?> value="120"><?php _e("120 Minutes (2 Hour)", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 150) echo "selected"; ?> value="150"><?php _e("150 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 180) echo "selected"; ?> value="180"><?php _e("180 Minutes (3 Hour)", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 210) echo "selected"; ?> value="210"><?php _e("210 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 240) echo "selected"; ?> value="240"><?php _e("240 Minutes (4 Hour)", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 270) echo "selected"; ?> value="270"><?php _e("270 Minutes", 'comptoneye'); ?></option>
                                    <option <?php if($BookingTimeSlot == 300) echo "selected"; ?> value="300"><?php _e("300 Minutes (5 Hour)", 'comptoneye'); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Booking Time Slot' ,'comptoneye'); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>

                        <tr>
                            <th align="right" scope="row"><?php _e("Display Service Cost", "comptoneye")?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <select name="show_service_cost" id="show_service_cost">
                                    <option value="yes" <?php if($AllCalendarSettings['show_service_cost'] == 'yes') echo "selected"; ?>><?php echo _e('Yes' ,'comptoneye'); ?></option>
                                    <option value="no" <?php if($AllCalendarSettings['show_service_cost'] == 'no') echo "selected"; ?>><?php echo _e('No' ,'comptoneye'); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Show or hide service cost at client booking form.", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>

                        <tr>
                            <th align="right" scope="row"><?php _e("Display Service Duration", "comptoneye")?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <select name="show_service_duration" id="show_service_duration">
                                    <option value="yes" <?php if($AllCalendarSettings['show_service_duration'] == 'yes') echo "selected"; ?>><?php echo _e('Yes' ,'comptoneye'); ?></option>
                                    <option value="no" <?php if($AllCalendarSettings['show_service_duration'] == 'no') echo "selected"; ?>><?php echo _e('No' ,'comptoneye'); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Show or hide service duration at client booking form.", "comptoneye"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Desktop Notification Tokens", "comptoneye")?></th>
                            <td align="center"><strong>:</strong></td>
                            <td><b><?php _e("Token for desktop notification", "comptoneye"); ?></b><br><p></p>
                                <textarea id="apcal_booking_instructions" name="apcal_booking_instructions" disabled style="width: 220px; height: 150px;"><?php if($AllCalendarSettings['apcal_booking_instructions']) echo esc_textarea($AllCalendarSettings['apcal_booking_instructions']); ?></textarea>
                                &nbsp;<a href="#" rel="tooltip" title="<?php _e("Token value is saved here"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th align="right" scope="row"><?php _e("Number of connected Notification Client", "comptoneye")?></th>
                            <td align="center"><strong>:</strong></td>
                            <td>
                                <div id="apcal_connected_client" name="conected_client"></div>
                                &nbsp;<a href="#" rel="tooltip" title="<?php _e("Number of notification client is shown here"); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">&nbsp;</th>
                            <td>
                                &nbsp;
                            </td>
                            <td>
                                <button name="save-settings" class="btn btn-success" id="save-settings" data-loading-text="Saving Settings" onclick="return SaveCalendarSettings('save-calendar-settings');" ><i class="fa fa-save"></i> <?php _e("Save", "comptoneye"); ?></button>
                                <div id="loading-img-calendar-settings" style="display: none;"><?php _e("Saving", "comptoneye"); ?>...<i class="fa fa-spinner fa-spin fa-2x"></i></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>

            <!--notification settings-->
            <div id="notification-settings" class="tab-pane fade">
                <fieldset>
                    <legend><?php _e("Manage Notification Settings", "comptoneye"); ?></legend>
                    <?php wp_nonce_field('appointment_noti_nonce_check','appointment_noti_nonce_check'); ?>
                    <table width="100%" class="table">
                        <tr>
                            <th colspan="2" scope="row"><?php _e('Enable', 'comptoneye'); ?></th>
                            <td width="3%"><strong>:</strong></td>
                            <td width="69%"><input name="enable" type="checkbox" id="enable" <?php if(get_option('emailstatus') == 'on') echo 'checked'; ?> />&nbsp;<a href="#" rel="tooltip" title="<?php _e('ON/OFF Notification', 'comptoneye'); ?>" ><i class="icon-question-sign"></i></a></td>
                            <td width="3%">&nbsp;</td>
                            <td width="3%">&nbsp;</td>
                            <td width="3%">&nbsp;</td>
                        </tr>
                        <?php $emailtype = get_option('emailtype'); ?>
                        <tr>
                            <th colspan="2" scope="row"><?php _e('Email Type', 'comptoneye'); ?></th>
                            <td><strong>:</strong></td>
                            <td>
                                <select name="emailtype" id="emailtype">
                                    <option value="0" <?php if(get_option('emailstatus') == 'off') echo "selected=selected";?>><?php _e('Select Type', 'comptoneye'); ?></option>
                                    <option value="wpmail" <?php if($emailtype == 'wpmail' && get_option('emailstatus') == 'on') echo 'selected';?>><?php _e('WP Mail', 'comptoneye'); ?></option>
                                    <option value="phpmail" <?php if($emailtype == 'phpmail' && get_option('emailstatus') == 'on') echo 'selected';?>><?php _e('PHP Mail', 'comptoneye'); ?></option>
                                    <option value="smtp" <?php if($emailtype == 'smtp' && get_option('emailstatus') == 'on') echo 'selected';?>><?php _e('SMTP Mail', 'comptoneye'); ?></option>
                                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Notification Type', 'comptoneye'); ?>" ><i class="icon-question-sign"></i></a>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                        $EmailDetails =  get_option('emaildetails');
                        if($EmailDetails) {
                            $EmailDetails = unserialize($EmailDetails);
                        }
                        ?>
                        <!--wp mail-->
                        <tr id="wpmaildetails1" style="display:none;">
                            <th colspan="2" scope="row"><?php _e('WP Mail Details', 'comptoneye'); ?></th>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="wpmaildetails2" style="display:none;">
                            <th scope="row">&nbsp;</th>
                            <th scope="row"><?php _e('Email', 'comptoneye'); ?></th>
                            <td><strong>:</strong></td>
                            <td><input name="wpemail" type="text" id="wpemail"  value="<?php if(isset($EmailDetails['wpemail'])) { echo esc_attr($EmailDetails['wpemail']); } ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin Email', 'comptoneye'); ?>" ><i class="icon-question-sign"></i></a></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>

                        <!--php mail-->
                        <tr id="phpmaildetails1" style="display:none;">
                            <th colspan="2" scope="row"><?php _e('PHPMail Details', 'comptoneye'); ?></th>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="phpmaildetails2" style="display:none;">
                            <th scope="row">&nbsp;</th>
                            <th scope="row"><?php _e('Email', 'comptoneye'); ?></th>
                            <td><strong>:</strong></td>
                            <td><input name="phpemail" type="text" id="phpemail" value="<?php if(isset($EmailDetails['phpemail'])) { echo esc_attr($EmailDetails['phpemail']); } ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin Email', 'comptoneye'); ?>" ><i  class="icon-question-sign"></i></a></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>

                        <!--smtp-->
                        <tr id="smtpdetails1" style="display:none;">
                            <th colspan="2" scope="row"><?php _e('SMTP Mail Details', 'comptoneye'); ?></th>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="smtpdetails2" style="display:none;">
                            <th width="9%" scope="row">&nbsp;</th>
                            <td width="10%" scope="row"><?php _e('Host Name', 'comptoneye'); ?></td>
                            <td><strong>:</strong></td>
                            <td><input name="hostname" type="text" id="hostname" class="inputhieght" value="<?php if(isset($EmailDetails['hostname'])) { echo esc_attr($EmailDetails['hostname']); } ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Host Name', 'comptoneye'); ?><br>Like Eg: <br>Gmail = smtp.gmail.com, <br>Yahoo = smtp.yahoo.com" ><i class="icon-question-sign"></i></a>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="smtpdetails3" style="display:none;">
                            <th scope="row">&nbsp;</th>
                            <td scope="row"><?php _e('Port Number', 'comptoneye'); ?></td>
                            <td><strong>:</strong></td>
                            <td><input name="portno" type="text" id="portno" value="<?php if(isset($EmailDetails['portno'])) { echo esc_attr($EmailDetails['portno']); } ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('SMTP Port Number', 'comptoneye'); ?><br>Gmail & Yahoo Port Number = 465" ><i class="icon-question-sign"></i></a></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="smtpdetails4" style="display:none;">
                            <th scope="row">&nbsp;</th>
                            <td scope="row"><?php _e('Email', 'comptoneye'); ?></td>
                            <td><strong>:</strong></td>
                            <td><input name="smtpemail" type="text" id="smtpemail" value="<?php if(isset($EmailDetails['smtpemail'])) { echo esc_attr($EmailDetails['smtpemail']); } ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin SMTP Email', 'comptoneye'); ?>" ><i class="icon-question-sign"></i></a></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr id="smtpdetails5" style="display:none;">
                            <th scope="row">&nbsp;</th>
                            <td scope="row"><?php _e('Password', 'comptoneye'); ?></td>
                            <td><strong>:</strong></td>
                            <td><input name="password" type="password" id="password" value="<?php if(isset($EmailDetails['password'])) { echo esc_attr($EmailDetails['password']); } ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin SMTP Email Password', 'comptoneye'); ?>"><i class="icon-question-sign"></i></a></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>

                        <tr>
                            <th colspan="2" scope="row">&nbsp;</th>
                            <td>&nbsp;</td>
                            <td>
                                <button name="save-notification-settings" class="btn btn-success" type="submit" id="save-notification-settings" onclick="return SaveNotificationSettings('save-notification-settings');"><i class="fa fa-save"></i> <?php _e('Save', 'comptoneye'); ?></button>
                                <div id="loading-img-notification-settings" style="display: none;"><?php _e("Saving", "comptoneye"); ?>...<i class="fa fa-spinner fa-spin fa-2x"></i></div>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </fieldset>
            </div>

            <!--notification message-->
            <div id="notification-message" class="tab-pane fade">
                <fieldset>
                <legend class="notification"><?php _e("Manage Notification Message", "comptoneye"); ?></legend>
                <div  class="row">
                    <div class="col-md-6">
                        
                        <?php wp_nonce_field('appointment_noti_msg_nonce_check','appointment_noti_msg_nonce_check'); ?>

                        <!--notify admin on new appointment-->
                 
                       <p  class="mesnottitle"><strong><?php _e("Notify Admin On New Appointment", "comptoneye"); ?></strong></p>
                        <p><?php _e("Subject", "comptoneye"); ?></p>
                        <input type="text" id="new-appointment-admin-subject" name="new-appointment-admin-subject" value="<?php echo esc_attr(get_option("new_appointment_admin_subject")); ?>" style="width:400px;">
                        <p><?php _e("Message Body", "comptoneye"); ?></p>
                        <textarea id="new-appointment-admin-body" name="new-appointment-admin-body" style="width: 400px; height: 280px;"><?php echo esc_textarea(get_option("new_appointment_admin_body")); ?></textarea><br>
                        <button name="save-message" class="btn btn-success" id="save-message" onclick="return SaveNotificationMessage('new-appointment-admin-message');"><i class="fa fa-save"></i> <?php _e('Save', 'comptoneye'); ?></button>
                        <div id="loading-img-new-appointment-admin-message" style="display: none;"><?php _e("Saving", "comptoneye"); ?>...<i class="fa fa-spinner fa-spin fa-2x"></i></div>
                    </div>

                    <div class="col-md-6">
                        <!--notify client on new appointment-->
                        <p class="mesnottitle"><strong><?php _e("Notify Client On New Appointment", "comptoneye"); ?></strong></p>
                        <p><?php _e("Subject", "comptoneye"); ?></p>
                        <input type="text" id="new-appointment-client-subject" name="new-appointment-client-subject" value="<?php echo esc_attr(get_option("new_appointment_client_subject")); ?>" style="width: 400px;">
                        <p><?php _e("Message Body", "comptoneye"); ?></p>
                        <textarea id="new-appointment-client-body" name="new-appointment-client-body" style="width: 400px; height: 280px;"><?php echo esc_textarea(get_option("new_appointment_client_body")); ?></textarea><br>
                        <button name="save-message" class="btn btn-success" id="save-message" onclick="return SaveNotificationMessage('new-appointment-client-message');"><i class="fa fa-save"></i> <?php _e('Save', 'comptoneye'); ?></button>
                        <div id="loading-img-new-appointment-client-message" style="display: none;"><?php _e("Saving", "comptoneye"); ?>...<i class="fa fa-spinner fa-spin fa-2x"></i></div>
                     
                    </div>
                </div>

                <div class="row">
                    <!--notify client on approve appointment-->
                    <div class="col-md-6">
                        <p class="mesnottitle"><strong><?php _e("Notify Client On Approve Appointment", "comptoneye"); ?></strong></p>
                        <p><?php _e("Subject", "comptoneye"); ?></p>
                        <input type="text" id="approve-appointment-client-subject" name="approve-appointment-client-subject" value="<?php echo esc_attr(get_option("approve_appointment_client_subject")); ?>" style="width: 400px;">
                        <p><?php _e("Message Body", "comptoneye"); ?></p>
                        <textarea id="approve-appointment-client-body" name="approve-appointment-client-body" style="width: 400px; height: 280px;"><?php echo esc_textarea(get_option("approve_appointment_client_body")); ?></textarea><br>
                        <button name="save-message" class="btn btn-success" id="save-message" onclick="return SaveNotificationMessage('approve-appointment-client-message');"><i class="fa fa-save"></i> <?php _e('Save', 'comptoneye'); ?></button>
                        <div id="loading-img-approve-appointment-client-message" style="display: none;"><?php _e("Saving", "comptoneye"); ?>...<i class="fa fa-spinner fa-spin fa-2x"></i></div>
                      
                    </div>

                    <!--notify client on cancel appointment-->
                    <div class="col-md-6">
                        <p class="mesnottitle"><strong><?php _e("Notify Client On Cancel Appointment", "comptoneye"); ?></strong></p>
                        <p><?php _e("Subject", "comptoneye"); ?></p>
                        <input type="text" id="cancel-appointment-client-subject" name="cancel-appointment-client-subject" value="<?php echo esc_attr(get_option("cancel_appointment_client_subject")); ?>" style="width: 400px;">
                        <p><?php _e("Message Body", "comptoneye"); ?></p>
                        <textarea id="cancel-appointment-client-body" name="cancel-appointment-client-body" style="width: 400px; height: 280px;"><?php echo esc_textarea(get_option("cancel_appointment_client_body")); ?></textarea><br>

                        <button name="save-message" class="btn btn-success" id="save-message" onclick="return SaveNotificationMessage('cancel-appointment-client-message');"><i class="fa fa-save"></i> <?php _e('Save', 'comptoneye'); ?></button>
                        <div id="loading-img-cancel-appointment-client-message" style="display: none;"><?php _e("Saving", "comptoneye"); ?>...<i class="fa fa-spinner fa-spin fa-2x"></i></div>
                       
                    </div>
                </div>
                </fieldset>
            </div>

        </div>
        <!--tabs-body-end-->
    </div>
</div>
<script type="text/javascript">
   
      jQuery(".tabmenu").click(function (){
        jQuery("#savedinfo").hide();
    });
   
</script>

<style type="text/css">
    .error{  color:#FF0000; }
    button.request{
        margin-top: -5%;
        float: right;
        margin-right: 35%
    }
    legend{
         color:#D9853B;
        font-size: 30px;
     }
     legend.notification{
        text-align: center;

     }
     p.mesnottitle {
    background: #ccc;
    text-align: center;
    padding: 10px 0px;
}
#notification-message #save-message {
    margin: 20px 0px;
}
</style>

          <!-- div to display the generated Instance ID token -->
          <div id="token_div" style="display: none;">
            <input id="token" style="word-break: break-all;" type="hidden"></input>
            <p class="something"> Token Successfully retrieved </p>
          </div>
          <!-- div to display the UI to allow the request for permission to
               notify the user. This is shown if the app has not yet been
               granted permission to notify. -->
          <div id="permission_div" style="display: none;">
            <button class="btn btn-primary request"
                    onclick="requestPermission()">Request Permission</button>
          </div>
          <!-- div to display messages received by this app. -->
          <div id="messages" style="display:none"></div>
        
<!-- Firebase -->
<!-- ********************************************************
     * TODO(DEVELOPER): Update Firebase initialization code:
        1. Go to the Firebase console: https://console.firebase.google.com/
        2. Choose a Firebase project you've created
        3. Click "Add Firebase to your web app"
        4. Replace the following initialization code with the code from the Firebase console:
-->
<!-- START INITIALIZATION CODE -->
<!-- PASTE FIREBASE INITIALIZATION CODE HERE -->
<!-- END INITIALIZATION CODE -->
<!-- ******************************************************** -->


<script type="text/javascript">
    /**
     * Calendar Settings Validation & Ajax PostData == appointment_cal_nonce_check
     */
    function SaveCalendarSettings(Action) {
        jQuery(".error").hide();
        // jQuery("#savedinfo").hide();
        var CalendarSlotTime = jQuery("#calendar_slot_time").val();
        var DayStartTime = jQuery("#day_start_time").val();
        var DayEndTime = jQuery("#day_end_time").val();
        var CalendarView = jQuery("#calendar_view").val();
        var CalendarStartDay = jQuery("#calendar_start_day").val();
        var BookingButtonText = jQuery("#booking_button_text").val();
        var BookingTimeSlot = jQuery("#booking_time_slot").val();
        var ServiceCost = jQuery("#show_service_cost").val();
        var ServiceDuration = jQuery("#show_service_duration").val();
        var BookingInstructions = jQuery("#apcal_booking_instructions").val();
        
        var wp_nonce = jQuery("#appointment_cal_nonce_check").val();

        var PostData1 = "Action=" + Action + "&CalendarSlotTime=" + CalendarSlotTime + "&DayStartTime=" + DayStartTime + "&DayEndTime=" + DayEndTime;
        var PostData2 = "&CalendarView=" + CalendarView + "&CalendarStartDay=" + CalendarStartDay + "&BookingButtonText=" + BookingButtonText;
        var PostData3 = "&BookingTimeSlot=" + BookingTimeSlot + "&ServiceCost=" + ServiceCost + "&ServiceDuration=" + ServiceDuration + "&BookingInstructions=" + BookingInstructions + '&wp_nonce=' + wp_nonce;
        var PostData = PostData1 + PostData2 + PostData3;
        jQuery("#loading-img-calendar-settings").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() { },
            success: function() {
                jQuery("#loading-img-calendar-settings").hide();
                jQuery("#savedinfo").html("Calendar settings successfully saved." );
                jQuery("#savedinfo").show();
                
            }
        });
    }

    /**
     * Notification Settings Page On Load Settings
     */
    jQuery(document).ready(function() {
        //on-load if check enable
        var emailtype = jQuery('#emailtype').val();
        if(jQuery('#enable').is(':checked')) {
            jQuery('#emailtype').attr("disabled", false);        //enable
            if(emailtype == 'wpmail') {
                jQuery('#smtpdetails1').hide();
                jQuery('#smtpdetails2').hide();
                jQuery('#smtpdetails3').hide();
                jQuery('#smtpdetails4').hide();
                jQuery('#smtpdetails5').hide();

                jQuery('#phpmaildetails1').hide();
                jQuery('#phpmaildetails2').hide();

                jQuery('#wpmaildetails1').show();
                jQuery('#wpmaildetails2').show();
            }

            if(emailtype == 'phpmail') {
                jQuery('#smtpdetails1').hide();
                jQuery('#smtpdetails2').hide();
                jQuery('#smtpdetails3').hide();
                jQuery('#smtpdetails4').hide();
                jQuery('#smtpdetails5').hide();

                jQuery('#phpmaildetails1').show();
                jQuery('#phpmaildetails2').show();

                jQuery('#wpmaildetails1').hide();
                jQuery('#wpmaildetails2').hide();
            }
            if(emailtype == 'smtp') {
                jQuery('#smtpdetails1').show();
                jQuery('#smtpdetails2').show();
                jQuery('#smtpdetails3').show();
                jQuery('#smtpdetails4').show();
                jQuery('#smtpdetails5').show();

                jQuery('#phpmaildetails1').hide();
                jQuery('#phpmaildetails2').hide();

                jQuery('#wpmaildetails1').hide();
                jQuery('#wpmaildetails2').hide();
            }
        } else {
            jQuery('#emailtype').attr("disabled", true);
        }

        //on-click
        jQuery('#enable').click(function(){

            jQuery(".error").hide();

            if (jQuery(this).is(':checked')) {
                jQuery('#emailtype').attr("disabled", false);
            }  else {
                jQuery('#emailtype').attr("disabled", true);
            }
        });

        //onchange email type
        jQuery('#emailtype').change(function(){
            var emailtype = jQuery('#emailtype').val();
            if(jQuery('#enable').is(':checked') && emailtype)  {
                if(emailtype=='wpmail') {
                    jQuery('#smtpdetails1').hide();
                    jQuery('#smtpdetails2').hide();
                    jQuery('#smtpdetails3').hide();
                    jQuery('#smtpdetails4').hide();
                    jQuery('#smtpdetails5').hide();

                    jQuery('#phpmaildetails1').hide();
                    jQuery('#phpmaildetails2').hide();

                    jQuery('#wpmaildetails1').show();
                    jQuery('#wpmaildetails2').show();
                }

                if(emailtype == 'phpmail') {
                    jQuery('#smtpdetails1').hide();
                    jQuery('#smtpdetails2').hide();
                    jQuery('#smtpdetails3').hide();
                    jQuery('#smtpdetails4').hide();
                    jQuery('#smtpdetails5').hide();

                    jQuery('#phpmaildetails1').show();
                    jQuery('#phpmaildetails2').show();

                    jQuery('#wpmaildetails1').hide();
                    jQuery('#wpmaildetails2').hide();
                }
                if(emailtype == 'smtp') {
                    jQuery('#smtpdetails1').show();
                    jQuery('#smtpdetails2').show();
                    jQuery('#smtpdetails3').show();
                    jQuery('#smtpdetails4').show();
                    jQuery('#smtpdetails5').show();

                    jQuery('#phpmaildetails1').hide();
                    jQuery('#phpmaildetails2').hide();

                    jQuery('#wpmaildetails1').hide();
                    jQuery('#wpmaildetails2').hide();
                }
            }
        });
    });

    /**
     * Notification Settings Validation & Ajax PostData
     */
    function SaveNotificationSettings(Action) {

        jQuery(".error").hide();
         // jQuery("#savedinfo").hide();
        //enable
        if (jQuery('#enable').is(':checked')) {
            var enable = "on";
        } else {
            var enable = "off";
        }

        var emailtype = jQuery('#emailtype').val();
        if(emailtype == 0) {
            jQuery("#emailtype").after('<span class="error">&nbsp;<br><strong><?php _e('Select email type' ,'comptoneye'); ?></strong></span>');
            return false;
        }

        //wp-email
        if(emailtype == 'wpmail') {
            var wpemail = jQuery('#wpemail').val();
            if(wpemail == '') {
                jQuery("#wpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Wp email required.' ,'comptoneye'); ?></strong></span>');
                return false;
            } else {
                var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(regex.test(wpemail) == false ) {
                    jQuery("#wpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid wp email.' ,'comptoneye'); ?></strong></span>');
                    return false;
                }
            }
            var PostData = "Action=" + Action + "&emailtype=" + emailtype + "&wpemail=" + wpemail + "&enable=" + enable;
        }

        //php-email
        if(emailtype == 'phpmail') {
            var phpemail = jQuery('#phpemail').val();
            if(phpemail == '') {
                jQuery("#phpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Php email required.' ,'comptoneye'); ?></strong></span>');
                return false;
            } else {
                var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(regex.test(phpemail) == false ) {
                    jQuery("#phpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid php email.' ,'comptoneye'); ?></strong></span>');
                    return false;
                }
            }
            var PostData = "Action=" + Action + "&emailtype=" + emailtype + "&phpemail=" + phpemail + "&enable=" + enable;
        }

        //smtp
        if(emailtype == 'smtp') {
            var hostname = jQuery('#hostname').val();
            if(hostname == '') {
                jQuery("#hostname").after('<span class="error">&nbsp;<br><strong><?php _e('Host name required.' ,'comptoneye'); ?></strong></span>');
                return false;
            }

            var portno = jQuery('#portno').val();
            if(portno == '') {
                jQuery("#portno").after('<span class="error">&nbsp;<br><strong><?php _e('Port number required.' ,'comptoneye'); ?></strong></span>');
                return false;
            }
            var portnoRes = isNaN(portno);
            if(portnoRes == true) {
                jQuery("#portno").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid port number.' ,'comptoneye'); ?></strong></span>');
                return false;
            }

            var smtpemail = jQuery('#smtpemail').val();
            if(smtpemail == '') {
                jQuery("#smtpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Email required' ,'comptoneye'); ?></strong></span>');
                return false;
            } else {
                var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(regex.test(smtpemail) == false ) {
                    jQuery("#smtpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid smtp email.' ,'comptoneye'); ?></strong></span>');
                    return false;
                }
            }

            var password = jQuery('#password').val();
            if(password == '') {
                jQuery("#password").after('<span class="error">&nbsp;<br><strong><?php _e('Password required.' ,'comptoneye'); ?></strong></span>');
                return false;
            }
            var PostData = "Action=" + Action + "&emailtype=" + emailtype + "&hostname=" + hostname + "&portno=" + portno + "&smtpemail=" + smtpemail + "&password=" + password + "&enable=" + enable;
        }
        
        var wp_nonce_noti = jQuery('#appointment_noti_nonce_check').val();
        PostData += '&wp_nonce_noti=' + wp_nonce_noti;

        jQuery('#enable').is(':checked')
        jQuery("#loading-img-notification-settings").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,

            complete : function() { },
            success: function() {
                jQuery("#loading-img-notification-settings").hide();
                // alert("<?php _e("Notification settings successfully saved.", "comptoneye"); ?>");
                 jQuery("#savedinfo").html('<?php _e("Notification settings successfully saved.", "comptoneye"); ?>');
                 jQuery("#savedinfo").show();
            }
        });
    }

    /***
     *  Notification Message Validation & Ajax PostData
     */
    function SaveNotificationMessage(Action) {
        //new app admin msg
        if(Action == "new-appointment-admin-message"){
            var Subject = jQuery("#new-appointment-admin-subject").val();
            var Body = jQuery("#new-appointment-admin-body").val();
            var PostData = "Action=" + Action + "&Subject=" + Subject + "&Body=" + Body;
        }

        //new app client msg
        if(Action == "new-appointment-client-message"){
            var Subject = jQuery("#new-appointment-client-subject").val();
            var Body = jQuery("#new-appointment-client-body").val();
            var PostData = "Action=" + Action + "&Subject=" + Subject + "&Body=" + Body;
        }

        //approve app client msg
        if(Action == "approve-appointment-client-message"){
            var Subject = jQuery("#approve-appointment-client-subject").val();
            var Body = jQuery("#approve-appointment-client-body").val();
            var PostData = "Action=" + Action + "&Subject=" + Subject + "&Body=" + Body;
        }

        //cancel app client msg
        if(Action == "cancel-appointment-client-message"){
            var Subject = jQuery("#cancel-appointment-client-subject").val();
            var Body = jQuery("#cancel-appointment-client-body").val();
            var PostData = "Action=" + Action + "&Subject=" + Subject + "&Body=" + Body;
        }
        
        var wp_nonce_noti_msg = jQuery("#appointment_noti_msg_nonce_check").val();
        PostData += '&wp_nonce_noti_msg=' + wp_nonce_noti_msg;

        jQuery("#loading-img-" + Action).show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() { },
            success: function() {
                jQuery("#loading-img-" + Action).hide();
                jQuery("#savedinfo").html('<?php _e("Notification Message successfully saved.", "comptoneye"); ?>');
                // alert("<?php  ?>");
                 jQuery("#savedinfo").show();
            }
        });
    }
</script>


<?php //Saving Settings
if(isset($_POST['Action'])) {
    echo $Action = $_POST['Action'];
    //print_r($_POST);

    /**
     * Saving Calendar Settings
     */
    if($Action == "save-calendar-settings") {
        
        if( !wp_verify_nonce($_POST['wp_nonce'],'appointment_cal_nonce_check') ){
            print 'Sorry, your nonce did not verify.';  exit;
        }
        

        $CalendarSettingsArray = array(
            'calendar_slot_time' => sanitize_text_field( $_POST['CalendarSlotTime'] ),
            'day_start_time' => sanitize_text_field( $_POST['DayStartTime'] ),
            'day_end_time' => sanitize_text_field( $_POST['DayEndTime'] ),
            'calendar_view' => sanitize_text_field( $_POST['CalendarView'] ),
            'calendar_start_day' => sanitize_text_field( $_POST['CalendarStartDay'] ),
            'booking_button_text' => sanitize_text_field( $_POST['BookingButtonText'] ),
            'booking_time_slot' => sanitize_text_field( $_POST['BookingTimeSlot'] ),
            'show_service_cost' => sanitize_text_field( $_POST['ServiceCost'] ),
            'show_service_duration' => sanitize_text_field( $_POST['ServiceDuration'] ),
            'apcal_booking_instructions' => wp_kses_post( force_balance_tags( $_POST['BookingInstructions'] ) ),
        );
        update_option('apcal_calendar_settings', serialize($CalendarSettingsArray));
    }

    /**
     * Saving Notification Settings
     */
    if($Action == "save-notification-settings") {
        
        if( !wp_verify_nonce($_POST['wp_nonce_noti'],'appointment_noti_nonce_check') ){
            print 'Sorry, your nonce did not verify.';  exit;
        }
        
        if(isset($_POST['enable']) == 'on') {
            //wp-mail
            if($_POST['emailtype'] == 'wpmail') {
                update_option('emailstatus', sanitize_text_field( $_POST['enable'] ) );
                update_option('emailtype', sanitize_text_field( $_POST['emailtype'] ) );

                $EmailDetails =  array ( 'wpemail' => sanitize_email( $_POST['wpemail'] ) );
                update_option( 'emaildetails', serialize($EmailDetails));
            }

            //php-mail
            if($_POST['emailtype'] == 'phpmail')
            {
                update_option('emailstatus', sanitize_text_field( $_POST['enable'] ) );
                update_option('emailtype', sanitize_text_field( $_POST['emailtype'] ) );
                $EmailDetails =  array ( 'phpemail' => sanitize_email( $_POST['phpemail'] ) );
                update_option('emaildetails', serialize($EmailDetails));
            }

            //smtp mail
            if($_POST['emailtype'] == 'smtp') {
                update_option('emailstatus', sanitize_text_field( $_POST['enable'] ) );
                update_option('emailtype', sanitize_text_field( $_POST['emailtype'] ) );
                $EmailDetails =  array ( 'hostname' => sanitize_text_field( $_POST['hostname'] ),
                    'portno' => intval( $_POST['portno'] ),
                    'smtpemail' => sanitize_email( $_POST['smtpemail'] ),
                    'password' => sanitize_text_field( $_POST['password'] ),
                );
                update_option('emaildetails', serialize($EmailDetails));
            }
        } else {
            delete_option('emailstatus');
            delete_option('emailtype');
            delete_option('emaildetails');
        }
    }

    /**
     * Saving Notification Message
     */
    if($Action == "new-appointment-admin-message") {
        
        if( !wp_verify_nonce($_POST['wp_nonce_noti_msg'],'appointment_noti_msg_nonce_check') ){
            print 'Sorry, your nonce did not verify.';  exit;
        }
        
        $Subject = sanitize_text_field( $_POST['Subject'] );
        $Body = wp_kses_post( force_balance_tags( $_POST['Body'] ) );
        update_option("new_appointment_admin_subject", $Subject);
        update_option("new_appointment_admin_body", $Body);
    }

    if($Action == "new-appointment-client-message") {
        
        if( !wp_verify_nonce($_POST['wp_nonce_noti_msg'],'appointment_noti_msg_nonce_check') ){
            print 'Sorry, your nonce did not verify.';  exit;
        }
        
        $Subject = sanitize_text_field( $_POST['Subject'] );
        $Body = wp_kses_post( force_balance_tags( $_POST['Body'] ) );
        update_option("new_appointment_client_subject", $Subject);
        update_option("new_appointment_client_body", $Body);
    }

    if($Action == "approve-appointment-client-message") {
        
        if( !wp_verify_nonce($_POST['wp_nonce_noti_msg'],'appointment_noti_msg_nonce_check') ){
            print 'Sorry, your nonce did not verify.';  exit;
        }
        
        $Subject = sanitize_text_field( $_POST['Subject'] );
        $Body = wp_kses_post( force_balance_tags( $_POST['Body'] ) );
        update_option("approve_appointment_client_subject", $Subject);
        update_option("approve_appointment_client_body", $Body);
    }

    if($Action == "cancel-appointment-client-message") {
        
        if( !wp_verify_nonce($_POST['wp_nonce_noti_msg'],'appointment_noti_msg_nonce_check') ){
            print 'Sorry, your nonce did not verify.';  exit;
        }
        
        $Subject = sanitize_text_field( $_POST['Subject'] );
        $Body = wp_kses_post( force_balance_tags( $_POST['Body'] ) );
        update_option("cancel_appointment_client_subject", $Subject);
        update_option("cancel_appointment_client_body", $Body);
    }
}
?>
<style type="text/css">
    div#savedinfo {
    color: green;
    text-align: center;
    font-size: 2em;
}
p.something {
    display: none;
}
/*div#notification-message {
    text-align: center;
}*/
</style>

<?php
$var =$AllCalendarSettings['apcal_booking_instructions'];
        $token= explode ('tokenseperator',$var);
         echo '<pre>';
         print_r($token);
         echo '</pre>';
?>
<script src="https://www.gstatic.com/firebasejs/3.7.4/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBseYF486Pjyrra6qfY_5FecJApGRWptSs",
    authDomain: "comptoneyes.firebaseapp.com",
    databaseURL: "https://comptoneyes.firebaseio.com",
    storageBucket: "comptoneyes.appspot.com",
    messagingSenderId: "422158753726"
  };
  firebase.initializeApp(config);
</script>
<script>
  // [START get_messaging_object]
  // Retrieve Firebase Messaging object.
  const messaging = firebase.messaging();
  // [END get_messaging_object]

  // IDs of divs that display Instance ID token UI or request permission UI.
  const tokenDivId = 'token_div';
  const permissionDivId = 'permission_div';

  // [START refresh_token]
  // Callback fired if Instance ID token is updated.
  messaging.onTokenRefresh(function() {
    messaging.getToken()
    .then(function(refreshedToken) {
      console.log('Token refreshed.');
      // Indicate that the new Instance ID token has not yet been sent to the
      // app server.
      setTokenSentToServer(false);
      // Send Instance ID token to app server.
      sendTokenToServer(refreshedToken);
      // [START_EXCLUDE]
      // Display new Instance ID token and clear UI of all previous messages.
      resetUI();
      // [END_EXCLUDE]
    })
    .catch(function(err) {
      console.log('Unable to retrieve refreshed token ', err);
      //showToken('Unable to retrieve refreshed token ', err);
      alert("unable to return token");
    });
  });
  // [END refresh_token]

  // [START receive_message]
  // Handle incoming messages. Called when:
  // - a message is received while the app has focus
  // - the user clicks on an app notification created by a sevice worker
  // //   `messaging.setBackgroundMessageHandler` handler.
  messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    // console.log(payload.notification.title);
    // [START_EXCLUDE]
    // Update the UI to include the received message.
    appendMessage(payload);
    // [END_EXCLUDE]
  });
  // // [END receive_message]
  

  function resetUI() {
    clearMessages();
    //showToken('loading...');
    // [START get_token]
    // Get Instance ID token. Initially this makes a network call, once retrieved
    // subsequent calls to getToken will return from cache.
    messaging.getToken()
    .then(function(currentToken) {
      if (currentToken) {
        sendTokenToServer(currentToken);
        updateUIForPushEnabled(currentToken);
      } else {
        // Show permission request.
        console.log('No Instance ID token available. Request permission to generate one.');
        // Show permission UI.
        updateUIForPushPermissionRequired();
        setTokenSentToServer(false);
      }
    })
    .catch(function(err) {
      console.log('An error occurred while retrieving token. ', err);
     // showToken('Error retrieving Instance ID token. ', err);
      alert("Error Token recieving");
      setTokenSentToServer(false);
    });
  }
  // [END get_token]

  function showToken(currentToken) {
    // Show token in console and UI.
    // alert(currentToken);
    var prevtoken = document.getElementById('apcal_booking_instructions').value;
    token = prevtoken.split('tokenseperator');
    <?php if (empty($token[0])|| !$token ): ?>
        document.getElementById('apcal_connected_client').innerHTML= "<h6 class='client'>No client yet </h6>";
        

<?php else: 

?>
document.getElementById('apcal_connected_client').innerHTML ="<h6 class='client'>" + <?php echo sizeof($token);
 ?> + "</h6>";
<?php endif; ?>

    if(token.includes(currentToken)){
        document.getElementById('apcal_booking_instructions').value= prevtoken;
    }
    else {
        if(prevtoken==""){
            document.getElementById('apcal_booking_instructions').value=currentToken;
        }
        else {
            document.getElementById('apcal_booking_instructions').value= prevtoken +"tokenseperator" +currentToken;
        }
}
    // var tokenElement = document.querySelector('#token');
    // tokenElement.textContent = currentToken;
  }

  // Send the Instance ID token your application server, so that it can:
  // - send messages back to this app
  // - subscribe/unsubscribe the token from topics
  function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer()) {
      console.log('Sending token to server...');
      // TODO(developer): Send the current token to your server.
      setTokenSentToServer(true);
    } else {
      console.log('Token already sent to server so won\'t send it again ' +
          'unless it changes');
    }

  }

  function isTokenSentToServer() {
    if (window.localStorage.getItem('sentToServer') == 1) {
          return true;
    }
    return false;
  }

  function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
  }

  function showHideDiv(divId, show) {
    const div = document.querySelector('#' + divId);
    if (show) {
      div.style = "display: visible";
    } else {
      div.style = "display: none";
    }
  }

  function requestPermission() {
    console.log('Requesting permission...');
    // [START request_permission]
    messaging.requestPermission()
    .then(function() {
      console.log('Notification permission granted.');
      // TODO(developer): Retrieve an Instance ID token for use with FCM.
      // [START_EXCLUDE]
      // In many cases once an app has been granted notification permission, it
      // should update its UI reflecting this.
      resetUI();
      // [END_EXCLUDE]
    })
    .catch(function(err) {
      console.log('Unable to get permission to notify.', err);
    });
    // [END request_permission]
  }

  function deleteToken() {
    // Delete Instance ID token.
    // [START delete_token]
    messaging.getToken()
    .then(function(currentToken) {
      messaging.deleteToken(currentToken)
      .then(function() {
        console.log('Token deleted.');
        setTokenSentToServer(false);
        // [START_EXCLUDE]
        // Once token is deleted update UI.
        resetUI();
        // [END_EXCLUDE]
      })
      .catch(function(err) {
        console.log('Unable to delete token. ', err);
      });
      // [END delete_token]
    })
    .catch(function(err) {
      console.log('Error retrieving Instance ID token. ', err);
      showTokenError('Error retrieving Instance ID token. ', err);
      //alert("unable to retrieve token");
    });

  }

  // Add a message to the messages element.
  function appendMessage(payload) {
    const messagesElement = document.querySelector('#messages');
    const dataHeaderELement = document.createElement('h5');
    const dataElement = document.createElement('pre');
    dataElement.style = 'overflow-x:hidden;'
    dataHeaderELement.textContent = 'Received message:';
    dataElement.textContent = JSON.stringify(payload, null, 2);
    messagesElement.appendChild(dataHeaderELement);
    messagesElement.appendChild(dataElement);
  }

  // Clear the messages element of all children.
  function clearMessages() {
    const messagesElement = document.querySelector('#messages');
    while (messagesElement.hasChildNodes()) {
      messagesElement.removeChild(messagesElement.lastChild);
    }
  }

  function updateUIForPushEnabled(currentToken) {
    showHideDiv(tokenDivId, true);
    showHideDiv(permissionDivId, false);
    showToken(currentToken);
  }

  function updateUIForPushPermissionRequired() {
    showHideDiv(tokenDivId, false);
    showHideDiv(permissionDivId, true);
  }

  resetUI();
</script>
<style>
h6.client {
    font-size: 30px;
}
</style>