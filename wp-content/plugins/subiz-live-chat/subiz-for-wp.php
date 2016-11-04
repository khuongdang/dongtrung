<?php
/*
  Plugin Name: Subiz Live Chat
  Plugin URI: http://support.subiz.com/support/solutions/articles/76904-subiz-plugins
  Description: Subiz live chat plugin offers an excellent customer interaction platform where sales and customer service team can communicate directly with visitors, fulfil any enquiry in real-time, and actively receive feedback
  Version: 1.1
  Author: mrsubiz
  Author URI: http://subiz.com
  License: GPL3
 */
# Init && Load plugin
$sfw_plugin = 'subiz-live-chat';
$sfw_plugin_url = get_option('siteurl') . '/' . PLUGINDIR . '/subiz-live-chat/';
$sfw_domain = 'SubizForWP';
load_plugin_textdomain($sfw_domain, 'wp-content/plugins/subiz-live-chat');
add_action('init', 'sfw_init');
add_action('wp_footer', 'sfw_insert');
# Init subiz for wordpress 
if (isset($_POST['subiz_licence_id'])) {
    $subiz_licence_id_ch = $_POST['subiz_licence_id'];
    update_option('subiz_licence_id', $subiz_licence_id_ch, $deprecated = null, 'yes');
}

function sfw_insert() {
    $subiz_licence_id = get_option('subiz_licence_id');
    echo '<script type="text/javascript">window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",' . $subiz_licence_id . ']);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);</script>';
}

function sfw_init() {
    if (function_exists('current_user_can') && current_user_can('manage_options')) {
        $subiz_licence_id = get_option('subiz_licence_id');
        add_action('admin_menu', 'sfw_add_settings_page');
    }
    if (!function_exists('get_plugins')) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    # Subiz check verified email && subiz widget code
    $subiz_licence_id = get_option('subiz_licence_id');
}

function sfw_add_settings_page() {

    function sfw_settings_page() {
        $subiz_licence_id_chk = get_option('subiz_licence_id');
        global $sfw_domain, $sfw_plugin_url, $subiz_widget_code;
        ?>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $sfw_plugin_url . 'subiz-for-wp.js' ?>"></script>
        <div class="wrap">
        <?php screen_icon() ?>
            <h2>Subiz Live Chat</h2>
            <div class="metabox-holder meta-box-sortables ui-sortable pointer">
                <div class="postbox" id="subizActivateBox" style="float:left;width:30em;margin-right:20px;">
                    <h3 class="hndle" style="text-align:center"><span>Subiz Live chat - Set up your Subiz Account</span></h3>
                    <div class="inside" style="padding: 0 10px;">
                        <p style="text-align:center"><a target="_blank" href="http://subiz.com/?utm_source=plugin_wp&utm_medium=plugin&utm_campaign=plugin_wp1.1" title="Subiz Live chat - Live support Solution for Business websites"><img src="<?php echo($sfw_plugin_url) ?>subiz-logo.png" width="173" height="49" alt="Subiz Logo" /></a></p>

                        <form  name="subiz_install_widget" id="subiz_install_widget" method="post" action="">
                                    <?php settings_fields('subiz-live-chat-group'); ?>
                            <p style="text-align:center">
                                <strong><?php
                                    if (isset($subiz_licence_id_chk) && $subiz_licence_id_chk != '') {
                                        echo '<font color="green">Subiz Live chat has installed successfully</font>';
                                    } else {
                                        echo 'Enter subiz Licence ID to install the widget';
                                    }
                                    ?></strong>
                            </p>
                            <p style="text-align:center">
                                Licence ID: <input type="text" name="subiz_licence_id" id="subiz_licence_id" value="<?php echo $subiz_licence_id_chk?>" style="width:75%" />
                            </p>
                            <p class="submit" style="text-align:center">
                                <input id="subiz_activate_bt" type="submit" class="button-primary" value="<?php
                                if (isset($subiz_licence_id_chk) && $subiz_licence_id_chk != '') {
                                    echo 'Save Changes';
                                } else {
                                    echo 'Install Subiz Widget';
                                }
                                ?>" />
                            </p>
                            <p style="text-align:center">Don’t have an account? <a href="javascript: void(0)" id="subizBtRegister" title="Register for free subiz account">Register for FREE</a></p>
                        </form>
                        <img style="margin-left: 35%; display: none;" id="subiz_loading_action" src="<?php echo $sfw_plugin_url . 'loader.gif'; ?>">
                        <p style="text-align:center"><small class="nonessential">Entering incorrect account will result in an error!</small></p>
                    </div>
                </div>
                <div class="postbox" id="subizDisActivateBox" style="float:left;width:30em;margin-right:20px;display: none;">
                    <h3 class="hndle"><span>Subiz Live chat - Live support Solution for Business</span></h3>
                    <div class="inside" style="padding: 0 10px">
                        <p style="text-align:center"><a target="_blank" href="http://subiz.com/?utm_source=plugin_wp&utm_medium=plugin&utm_campaign=plugin_wp1.1" title="Subiz Live chat - Live support Solution for Business websites"><img src="<?php echo($sfw_plugin_url) ?>subiz-logo.png" width="173" height="49" alt="Subiz Logo" /></a></p>
                        <form name="subiz_register_widget" id="subiz_register_widget" method="post">
                            <p style="text-align:center"><strong>Register for free subiz account</strong></p>
                            <p>
                                Email: (<font style="color: red;">*</font>)<input type="text" name="subiz_register_email" id="subiz_register_email" value="" style="width:100%" />
                            </p>
                            <p>
                                Username: (<font style="color: red;">*</font>)<input type="text" name="subiz_register_name" id="subiz_register_name" value="" style="width:100%" />
                            </p>
                            <p class="submit" style="text-align:center">
                                <input type="submit" class="button-primary" value="Register Account" />
                            </p>
                            <p style="text-align:center">You have an account?  <a href="javascript: void(0)" id="subizBtActivate" title="Activate your subiz account">Install subiz widget</a></p>
                        </form>
                        <img style="margin-left: 35%; display: none;" id="subiz_loading_register" src="<?php echo $sfw_plugin_url . 'loader.gif'; ?>">
                        <p style="text-align:center"><small class="nonessential">You must login in your email account to activate and complete register subiz account!</small></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    add_submenu_page('options-general.php', 'Subiz Live Chat', 'Subiz Live Chat', 'manage_options', 'subiz-for-wp', 'sfw_settings_page');
}
?>