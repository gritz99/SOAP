<?php
function lenslider_settins_page() {
    $ls_settings = new LenSliderSettings();
    if(isset($_POST['ls_update_settings'])) {
        do_action('lenslider_save_settings', 
            LenSliderSettings::lenslider_make_settings_array(
                    $_POST/*array*/,
                    array(/*MAX limits default*/
                        LenSlider::$slidersLimitName => $ls_settings->slidersLimitDefault,
                        LenSlider::$bannersLimitName => $ls_settings->bannersLimitDefault,
                        LenSlider::$maxWidthName     => $ls_settings->imageWidthMAX,
                        LenSlider::$qualityName      => $ls_settings->imageQualityMAX,
                        LenSlider::$maxSizeName      => $ls_settings->imageFileSizeMAX
                    ),
                    array(/*MIN limits default*/
                        LenSlider::$maxWidthName     => $ls_settings->imageWidthMIN,
                        LenSlider::$qualityName      => $ls_settings->imageQualityMIN
                    ),
                    array('ls_update_settings')/*to unset*/
            )
        );
    }
    if(isset($_GET['noheader'])) require_once(ABSPATH.'wp-admin/admin-header.php');
    $settings_array = LenSlider::lenslider_get_array_from_wp_options(LenSlider::$settingsOption);?>
    <div class="wrap columns-2">
        <?php if(isset($_GET['message'])) echo "<div class=\"updated\"><p>".__("LenSlider settings updated", 'len-slider')."</p></div>";?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <!--div class="inner-sidebar">
                <div class="postbox">
                    <h3 class="hndle"><span><?php //_e("About LenSlider plugin:", 'len-slider')?></span></h3>
                    <div class="inside"><?php //_e("", 'len-slider')?></div>
                </div>
            </div-->
            <div class="has-sidebar">
                <div id="post-body-content" class="has-sidebar-content">
                    <h2><?php _e("LenSlider Settings", 'len-slider')?></h2>
                    <form id="ls_settings_form" method="post" action="<?php echo admin_url("admin.php?page={$ls_settings->settingsPage}&noheader=true")?>">
                        <h3><?php _e("General", 'len-slider')?></h3>
                        <table class="form-table flag-options">
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$slidersLimitName?>"><?php printf(__("Sliders limit<br /><span class=\"description\">(max: %d)</span>", 'len-slider'), $ls_settings->slidersLimitDefault)?></label></th>
                                <td><input name="<?php echo LenSlider::$slidersLimitName?>" type="text" id="<?php echo LenSlider::$slidersLimitName?>" size="5" value="<?php echo $settings_array[LenSlider::$slidersLimitName];?>" maxlength="2" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$bannersLimitName?>"><?php printf(__("Banners limit for each slider<br /><span class=\"description\">(max: %d)</span>", 'len-slider'), $ls_settings->bannersLimitDefault)?></label></th>
                                <td><input name="<?php echo LenSlider::$bannersLimitName?>" type="text" id="<?php echo LenSlider::$bannersLimitName?>" size="5" value="<?php echo $settings_array[LenSlider::$bannersLimitName];?>" maxlength="2" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$cacheName?>"><?php _e("Enable WordPress cache for sliders", 'len-slider')?></label></th>
                                <td><input name="<?php echo LenSlider::$cacheName?>" type="checkbox" id="<?php echo LenSlider::$cacheName?>" value="<?php echo $settings_array[LenSlider::$cacheName];?>" <?php @checked($settings_array[LenSlider::$cacheName], 1)?> /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$tipsyName?>"><?php _e("Tooltip hint", 'len-slider')?></label></th>
                                <td><input type="checkbox" name="<?php echo LenSlider::$tipsyName?>" id="<?php echo LenSlider::$tipsyName?>" value="<?php echo $settings_array[LenSlider::$tipsyName]?>" <?php @checked($settings_array[LenSlider::$tipsyName], 1)?> />  <label for="ls_tipsy"><?php _e("Enable <a href=\"http://onehackoranother.com/projects/jquery/tipsy/\" target=\"_blank\">tipsy</a> tooltip for control buttons", 'len-slider')?></label></td>
                            </tr>
                        </table>
                        <h3><?php _e("Images", 'len-slider')?></h3>
                        <table class="form-table flag-options">
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$maxSizeName?>"><?php printf(__("Upload images maximum size, MB<br /><span class=\"description\">(max: %d)</span>", 'len-slider'), $ls_settings->imageFileSizeMAX)?></label></th>
                                <td><input name="<?php echo LenSlider::$maxSizeName?>" type="text" id="<?php echo LenSlider::$maxSizeName?>" size="5" value="<?php echo $settings_array[LenSlider::$maxSizeName];?>" maxlength="2" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$maxWidthName?>"><?php printf(__("Maximum image width, px<br /><span class=\"description\">(min: %1d; max: %2d)<br />proportions are kept</span>", 'len-slider'), $ls_settings->imageWidthMIN, $ls_settings->imageWidthMAX)?></label></th>
                                <td><input name="<?php echo LenSlider::$maxWidthName?>" type="text" id="<?php echo LenSlider::$maxWidthName?>" size="5" value="<?php echo $settings_array[LenSlider::$maxWidthName];?>" maxlength="4" /></td>
                            </tr>
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$qualityName?>"><?php printf(__("Maximum quality of uploaded image<br /><span class=\"description\">(min: %1d; max: %2d)<br />percents</span>", 'len-slider'), $ls_settings->imageQualityMIN, $ls_settings->imageQualityMAX)?></label></th>
                                <td><input name="<?php echo LenSlider::$qualityName?>" type="text" id="<?php echo LenSlider::$qualityName?>" size="5" value="<?php echo $settings_array[LenSlider::$qualityName];?>" maxlength="3" /></td>
                            </tr>
                        </table>
                        <h3><?php _e("Help", 'len-slider')?></h3>
                        <table class="form-table flag-options">
                            <tr>
                                <th scope="row" width="250"><label for="<?php echo LenSlider::$backlink?>"><?php _e("I would like to support plugin creators and place a plugin link to my site", 'len-slider')?></label></th>
                                <td><input type="checkbox" name="<?php echo LenSlider::$backlink?>" id="<?php echo LenSlider::$backlink?>" value="<?php if(!empty($settings_array[LenSlider::$backlink])) echo $settings_array[LenSlider::$backlink];?>" <?php if(!empty($settings_array[LenSlider::$backlink])) checked($settings_array[LenSlider::$backlink], 1)?> /> <?php if(!empty($settings_array[LenSlider::$backlink]) && checked($settings_array[LenSlider::$backlink], 1, false)) echo "<span style=\"margin-left:5px;font-weight:bold;color:red\">".__("Thanks! Let Your dreams come true!")."</span>";?></td>
                            </tr>
                        </table>
                        <br /><input type="submit" class="button-primary" name="ls_update_settings" value="<?php _e("Update settings", 'len-slider')?>" />
                    </form>
                </div><!--has-sidebar-content-->
            </div><!--has-sidebar-->
        </div><!--metabox-holder-->
    </div><!--wrap-->
<?php }?>