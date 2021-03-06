<?php defined('ABSPATH') OR die('No direct access.');
/*
Plugin Name: Email Encoder Bundle - Protect Email Address
Plugin URI: http://www.freelancephp.net/email-encoder-php-class-wp-plugin/
Description: Protect email addresses on your site and hide them from spambots by using an encoding method. Easy to use, flexible .
Author: Victor Villaverde Laan
Version: 1.1.0
Author URI: http://www.freelancephp.net
License: Dual licensed under the MIT and GPL licenses
Text Domain: email-encoder-bundle
Domain Path: /languages
*/

// constants
if (!defined('EMAIL_ENCODER_BUNDLE_VERSION')) { define('EMAIL_ENCODER_BUNDLE_VERSION', '1.1.0'); }
if (!defined('EMAIL_ENCODER_BUNDLE_FILE')) { define('EMAIL_ENCODER_BUNDLE_FILE', __FILE__); }
if (!defined('EMAIL_ENCODER_BUNDLE_KEY')) { define('EMAIL_ENCODER_BUNDLE_KEY', 'WP_Email_Encoder_Bundle'); }
if (!defined('EMAIL_ENCODER_BUNDLE_DOMAIN')) { define('EMAIL_ENCODER_BUNDLE_DOMAIN', 'email-encoder-bundle'); }
if (!defined('EMAIL_ENCODER_BUNDLE_OPTIONS_NAME')) { define('EMAIL_ENCODER_BUNDLE_OPTIONS_NAME', 'WP_Email_Encoder_Bundle_options'); }
if (!defined('EMAIL_ENCODER_BUNDLE_ADMIN_PAGE')) { define('EMAIL_ENCODER_BUNDLE_ADMIN_PAGE', 'email-encoder-bundle-settings'); }

// check plugin compatibility
if (isset($wp_version)
            && version_compare(preg_replace('/-.*$/', '', $wp_version), '3.4', '>=')
            && version_compare(phpversion(), '5.2.4', '>=')) {

    // include classes
    require_once('includes/class-eeb-admin.php');
    require_once('includes/class-eeb-site.php');
    require_once('includes/template-functions.php');

    // create instance
    $Eeb_Site = Eeb_Site::getInstance();

    // handle AJAX request
    if (!empty($_GET['ajaxEncodeEmail'])):
        // input vars
        $method = $_GET['method'];
        $email = $_GET['email'];
        $display = (empty($_GET['display'])) ? $email : $_GET['display'];

        echo $Eeb_Site->encode_email($email, $display, '', $method, true);
        exit;
    endif;

    // for testing purposes
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . 'wp/plugins/wp-plugin-tester/tests/test-email-encoder-bundle.php')) {
        require_once($_SERVER['DOCUMENT_ROOT'] . 'wp/plugins/wp-plugin-tester/wp-plugin-tester.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . 'wp/plugins/wp-plugin-tester/tests/test-email-encoder-bundle.php');
    }

} else {

    // set error message
    if (!function_exists('eeb_error_notice')):
        function eeb_error_notice() {
            $plugin_title = get_admin_page_title();

            echo '<div class="error">'
                . sprintf(__('<p>Warning - The plugin <strong>%s</strong> requires PHP 5.2.4+ and WP 3.4+.  Please upgrade your PHP and/or WordPress.'
                             . '<br/>Disable the plugin to remove this message.</p>'
                             , EMAIL_ENCODER_BUNDLE_DOMAIN), $plugin_title)
                . '</div>';
        }

        add_action('admin_notices', 'eeb_error_notice');
    endif;

}

/* ommit PHP closing tag, to prevent unwanted whitespace at the end of the parts generated by the included files */