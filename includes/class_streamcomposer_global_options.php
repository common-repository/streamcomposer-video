<?php

/**
 * StreamComposer global options module.
 *
 * @package   StreamComposer
 * @copyright Copyright (c) 2016 StreamComposer
 * @license   GPL-2.0+
 * @since     1.0.0
 */
class streamcomposer_global_options
{
    function __construct()
    {
    }

    public static function wrongApiKeyMessage($rootPage=false)
    {
        ?>
        <div class="error notice">
            <h2>Wrong or missing key</h2>
            <p>It does not look as if you have entered a valid API Key for StreamComposer.</p>
            <?php
            if ($rootPage) {
                ?>
                <p>If you do not know what the key is, please go <a
                            href="//app.streamcomposer.com/home/#/profile/advanced" target="_blank">here</a> to get it
                </p>
                <?php
            } else {
                ?>
                <p>Please go <a href="<?php echo admin_url('admin.php?page=streamcomposer_options'); ?>">here</a> enter
                    a
                    correct key.</p>
                <?php
            }
            ?>

        </div>
        <?php
    }

    public static function checkKey($apiKey)
    {
        $options = array(
            'headers' => array(
                'apitoken' => $apiKey
            )
        );
        $returnedData = wp_remote_get('https://app.streamcomposer.com/user/checkKey', $options);
        $returnedData['ok'] = $returnedData['response']['code'] == 200;
        $tokenReply = json_decode($returnedData['body']);
        if ($tokenReply) {
            $options = get_option('streamcomposer');
            $options['publickey'] = $tokenReply->data;
            update_option('streamcomposer', $options);

        }
        return $returnedData;
    }
}

$streamcomposer_global_options = new streamcomposer_global_options();