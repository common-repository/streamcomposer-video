<?php

/**
 * StreamComposer encoding list module.
 *
 * @package   StreamComposer
 * @copyright Copyright (c) 2016 StreamComposer
 * @license   GPL-2.0+
 * @since     1.0.0
 *
 * Using the StreamComposer API we check the status of all media that has not yet been encoded.
 * Adds elements to the encoding list table of all that is not yet encoed with a bar showing how far it have come.
 */
class streamcomposer_encoding_status
{
    private $privateToken = "";

    function __construct()
    {
        add_submenu_page('streamcomposer_options', 'Encoding status', 'Encoding status', 'manage_options', 'streamcomposer_encoding_status',
            array($this, 'status_page')
        );
        wp_enqueue_script("jquery");
        wp_enqueue_script('streamcomposer_encoding', plugins_url('/js/streamcomposer_encoding_tools.js', dirname(__FILE__)), array('jquery'), '1.0', true);
        $options = get_option('streamcomposer');
        $this->privateToken = $options['apiKey'];
    }


    /**
     * Function that generates the table it self.
     */
    function status_page()
    {
        $options = get_option('streamcomposer');
        $token = "";
        if (!streamcomposer_global_options::checkKey($options['apiKey'])['ok']) {
            streamcomposer_global_options::wrongApiKeyMessage();
            return;
        }
        if (isset($options['publickey'])) {
            $token = $options['publickey'];
        }
        ?>
        <script>
            var streamcomposer_token = "<?php echo $token;?>";
        </script>
        <div class="wrap">
            <h1><?= esc_html(get_admin_page_title()); ?></h1>
            <p class="description">
                Encoding is in progress. When the video(s) have been encoded they will be ready for being placed in a
                page on your site.
            </p>
            <table class="wp-list-table widefat plugins">
                <thead>
                <tr class="form-field">
                    <th class="manage-column column-description" style="width: 200px;">
                        Title
                    </th>
                    <th class="manage-column column-description">
                        Progress
                    </th>
                </tr>

                </thead>
                <tbody id="encodingStatusTable">

                </tbody>
            </table>
        </div>
        <script language="JavaScript">
            jQuery(document).ready(function ($) {
                encodingStatus();
            });
        </script>
        <?php
    }
}