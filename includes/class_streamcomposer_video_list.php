<?php
/**
 * StreamComposer video list module.
 *
 * @package   StreamComposer
 * @copyright Copyright (c) 2016 StreamComposer
 * @license   GPL-2.0+
 * @since     1.0.0
 *
 * Lists videos stored at StreamComposer using the API key that you can get from within StreamComposer
 */

class streamcomposer_video_list
{
    function __construct()
    {
        add_submenu_page('streamcomposer_options', 'Videos at StreamComposer', 'Video list', 'manage_options', 'streamcomposer_video_list',
            array($this, 'video_page')
        );
    }

    function video_page()
    {
        ?>

        <?php
        $options = get_option('streamcomposer');
        if (!streamcomposer_global_options::checkKey($options['apiKey'])['ok']) {
            streamcomposer_global_options::wrongApiKeyMessage();
        } else {
            $remoteOptions = array(
                'headers' => array(
                    'client-security-token' => $options['publickey']
                )
            );
            $medias = array();
            $response = wp_remote_get('https://app.streamcomposer.com/media/listVideoByTags', $remoteOptions);
            $data = json_decode($response['body']);
            if ($data && $data->data && $data->data->medias) {
                $medias = $data->data->medias;
            }
            ?>
            <div class="wrap">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                <p class="description">
                    Below you see a list of videos at your account at StreamComposer. If you would like to preview the
                    video, just press the play button at the left side of the row.
                </p>
                <table class="wp-list-table widefat plugins videoList" cellspacing="10">
                    <thead>
                    <tr class="form-field">
                        <th class="manage-column column-description" style="width: 20px;">
                            Preview
                        </th>
                        <th class="manage-column column-description" style="max-width: 200px;">
                            Title
                        </th>
                        <th class="manage-column column-description" style="max-width: 300px;">
                            Description
                        </th>
                        <th class="manage-column column-description">
                            Embed URL
                        </th>
                    </tr>
                    </thead>
                    <tbody id="encodingStatusTable">
                    <?php
                    foreach ($medias as $key => $media) {
                        $origDescription = strip_tags($media->description);
                        $description = str_replace('"', '\"', $origDescription);
                        if (strlen($description)) {
                            $description = substr($description, 0, 100) . "...";
                        }
                        echo '<tr class="inactive">';
                        echo '<td class="column-description desc">' .
                            '<a href="https://app.streamcomposer.com/home/player/#/embed/' . $media->mediaid . '?autoplay=true" target="_blank">' .
                            '<span class="dashicons dashicons-controls-play"></a>' .
                            '</td>';
                        echo '<td class="column-description desc">' . $media->mediatitle . '</td>';
                        echo '<td class="column-description desc" style="max-width: 300px;" title="' . $origDescription . '">' . $description . '</td>';
                        echo '<td class="" >' .
                            '<input value="https://app.streamcomposer.com/play/' . $media->customerShortName . '/' . $media->shortname . '" onClick="this.select()" style="width: 100%"></td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
    }

}