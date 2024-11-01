<?php
/**
 * StreamComposer video upload module.
 *
 * @package   StreamComposer
 * @copyright Copyright (c) 2016 StreamComposer
 * @license   GPL-2.0+
 * @since     1.0.0
 */
class streamcomposer_video_upload
{
    function __construct()
    {
        add_submenu_page('streamcomposer_options', 'Upload video to StreamComposer', 'Upload', 'manage_options', 'streamcomposer_video_upload',
            array($this, 'upload_page')
        );
    }

    /**
     * Renders the upload page for uploading videos to StreamComposer
     */
    function upload_page()
    {
        $options = get_option('streamcomposer');
        if (!streamcomposer_global_options::checkKey($options['apiKey'])['ok']) {
            streamcomposer_global_options::wrongApiKeyMessage();
        } else {
            ?>
            <script language="JavaScript">
                var token = "<?php echo $options['publickey'];?>"
            </script>
            <?php
            wp_enqueue_script('streamcomposer_tools', plugins_url('/js/streamcomposer_upload_tools.js', dirname(__FILE__)), array('jquery'), '1.0', true);
            ?>
            <div class="wrap">
                <h1><?= esc_html(get_admin_page_title()); ?></h1>
                <p class="description">
                    To add a video to StreamComposer you must give it a title and select a video file.<br>
                    When the file has been chosen it is automatically uploaded to StreamComposer and when you press <strong>Add video</strong> the encoding will start.<br/>
                    After the encoding has finished the video will be ready for inserting into one of your pages.
                </p>
                <form role="form" enctype="multipart/form-data" method="post"
                      action="//app.streamcomposer.com/media/addVideo">
                    <table class="form-table">
                        <tbody>
                        <tr class="form-field form-required">
                            <th scope="row">
                                <label for="mediatitle" class="menu-name-label">Title</label>
                            </th>
                            <td><input type="text" name="mediatitle" class="menu-name regular-text menu-item-textbox"
                                       id="mediatitle" onkeyup="JavaScript: checkAddVideoBtn()"
                                       placeholder="Name of video">
                            </td>
                        </tr>
                        <tr class="form-field form-required">
                            <th scope="row">
                                <label for="filename" class="required">Select file</label>
                            </th>
                            <td>
                                <input type="text" name="filename" class="form-control" id="filename" readonly
                                       placeholder="Click to select file"  accept=".mpg, .avi, .mkv">
                            </td>
                        </tr>
                        <tr class="form-field">
                            <th scope="row">
                                <label for="filename" class="required">Upload progress</label>
                            </th>
                            <td>
                                <div class="progress">
                                    <div class="progressbar" role="progressbar" id="pctcontainer"
                                         aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="fileunique" id="fileunique">
                    <input type="button" onclick="addVideo()" class="button button-primary" value="Add video"
                           id="addVideoBtn"/>
                    <input type="hidden" name="client-security-token" id="apitoken" value="<?php echo $options['publickey']; ?>">
                </form>
            </div>
            <?php
        }
    }


}
