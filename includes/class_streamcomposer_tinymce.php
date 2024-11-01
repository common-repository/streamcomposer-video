<?php
/**
 * StreamComposer TinyMCE plugin module.
 *
 * @package   StreamComposer
 * @copyright Copyright (c) 2016 StreamComposer
 * @license   GPL-2.0+
 * @since     1.0.0
 */

class streamcomposer_tinymce
{
    public function __construct()
    {
        add_action('admin_head', array($this, 'streamcomposer_add_mce_button'));
    }

    function streamcomposer_add_mce_button()
    {
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        // check if WYSIWYG is enabled
        if (get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", array($this, "streamcomposer_add_tinymce_plugin"));
            add_filter('mce_buttons', array($this, 'streamcomposer_mce_button'));
        }
    }

    function streamcomposer_add_tinymce_plugin($plugin_array)
    {
        $plugin_array['streamcomposer_mce_button'] = plugins_url('../js/tinymce-plugin.js', __FILE__);
        return $plugin_array;
    }

    function streamcomposer_mce_button($buttons)
    {
        array_push($buttons, "streamcomposer_mce_button");
        return $buttons;
    }
}