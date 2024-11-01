<?php
/**
 * Plugin Name: StreamComposer Video
 * Plugin URI: https://www.streamcomposer.com
 * Description: Encode videos for web and mobile devices directly from WordPress simply by uploading a video in the administration interface of WordPress.
 * Version: 1.0.2
 * Author: StreamComposer
 * Author URI: https://streamcomposer.com
 * License: GPL2
 */

/* Copyright 2016 StreamComposer (email : jarl@streamcomposer.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

define('STREAMCOMPOSER_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once(STREAMCOMPOSER_PLUGIN_DIR . 'includes/class_streamcomposer_global_options.php');
require_once(STREAMCOMPOSER_PLUGIN_DIR . 'includes/class_streamcomposer_tinymce.php');
require_once(STREAMCOMPOSER_PLUGIN_DIR . 'includes/class_streamcomposer_encoding_status.php');
require_once(STREAMCOMPOSER_PLUGIN_DIR . 'includes/class_streamcomposer_video_list.php');
require_once(STREAMCOMPOSER_PLUGIN_DIR . 'includes/class_streamcomposer_video_upload.php');

class streamcomposer
{
    function __construct()
    {

        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_notices', array($this, 'acf_admin_notice'));

        $this->options = get_option('streamcomposer');
        if ($this->options['apiKey'] == '') {
            add_action('admin_notices', Array($this, 'auth_message'));
        }

        add_shortcode('streamcomposer-code', array($this, 'generate_streamcomposer_code'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    function enqueue_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style('streamcomposer-style', plugins_url('/css/main.css', __FILE__));
        wp_enqueue_script('resumable', plugins_url('/js/resumable.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script('streamcomposer-embed', plugins_url('/js/streamcomposer_player.js', __FILE__), array('jquery'));
        wp_enqueue_style('streamcomposer_main_css', plugins_url('/css/main.css', __FILE__));
    }


    function generate_streamcomposer_code($atts, $content = null)
    {
        if ($atts['width']) {
            $width = 'width="' . $atts['width'] . '"';
        }
        if ($atts['height']) {
            $height = 'height="' . $atts['height'] . '"';
        }

        $html = '<div class="streamcomposer-embed"><div class="streamcomposer-videoWrapper streamcomposer-video">' .
            '<iframe src="' . $atts['src'] . '" style="border:0px #ffffff none;" scrolling="no" frameborder="0" marginheight="0px"' .
            ' marginwidth="0px" allowfullscreen ' . $width . ' ' . $height . '></iframe>' .
            '</div></div>';
        return $html;
    }

    function auth_message()
    {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>You will need to install and configure
                <a href="https://www.streamcomposer.com/Pricing"
                   target="_blank">StreamComposer API</a> plugin in order for StreamComposer Plugin to interact
                directly with StreamComposer.
            </p>
        </div> <?php
    }

    function acf_admin_notice()
    {
        ?>
        <div class="notice error my-acf-notice" id="errorMessage" style="display: none">
            <p id="errorContainer">
            </p>
        </div>
        <div class="notice updated my-acf-notice" id="updateMessage" style="display: none">
            <p id="updateContainer">
            </p>
        </div>
        <?php
    }

    function admin_menu()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        add_menu_page(
            'StreamComposer API settings',
            'StreamComposer',
            'manage_options',
            'streamcomposer_options',
            array(
                $this,
                'options_page_html'
            )
        );
        new streamcomposer_encoding_status();
        new streamcomposer_video_list();
        new streamcomposer_video_upload();
    }

    function options_page_html()
    {
        // check user capabilities
        if (isset($_REQUEST['settings-updated'])) {
            $apiKey = $_POST['apikey'];
            $returnedData = streamcomposer_global_options::checkKey($apiKey);
            if ($returnedData['response']['code'] != 200 && trim($apiKey) != "") {
                $responseObject = json_decode($returnedData['body']);
                if (is_object($responseObject)) {
                    $responseBody = $responseObject->text;
                } else {
                    $responseBody = $responseObject;
                }
                $responseBody .= "<br/>Your API key is probably wrong, please click link below to verify it.";
                add_settings_error('streamcomposer_org_messages', 'streamcomposer_org_message', __($responseBody, 'streamcomposer_org'), 'error');
            } else {
                add_settings_error('streamcomposer_org_messages', 'streamcomposer_org_message', __("Settings stored", 'streamcomposer_org'), 'updated');
                $this->options = array(
                    'apiKey' => $apiKey
                );
                update_option('streamcomposer', $this->options);
                register_setting('streamcomposer_options', 'streamcomposer');
            }

        } else {
            if (!streamcomposer_global_options::checkKey($this->options['apiKey'])['ok']) {
                $keyOk = "block";
                streamcomposer_global_options::wrongApiKeyMessage(true);
            } else {
                $keyOk = "none";
            }
            //Test connection to API
        }
        settings_errors('streamcomposer_org_messages');

        ?>
        <div class="wrap">
            <h1><?= esc_html(get_admin_page_title()); ?></h1>
            <p class="description" style="display: <?php echo $keyOk; ?>">If you do not yet have a StreamComposer
                account, then you can
                register or buy <a
                        href="//www.streamcomposer.com/Pricing" target="_blank"> here</a>.</p>

            <form action="" method="post">
                <label>API key:
                    <input type="text" name="apikey" value="<?php echo $this->options['apiKey']; ?>"/>
                </label>
                <input type="hidden" name="settings-updated" value="true"/>
                <?php
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }
}

new streamcomposer();
new streamcomposer_tinymce();
