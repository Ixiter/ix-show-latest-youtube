<?php

/**
 * Ix_ShowLatestYt is a WordPress plugin that provides a shortcode to embed the latest video or the current live video from a YouTube channel into your post or page.
 * 
 * @package Ixiter WordPress Plugins
 * @subpackage IX Show Latest YouTube
 * @version 1.0
 * @author Peter Liebetrau <ixiter@ixiter.com>
 * @license GPL 3 or greater
 */
if (!class_exists('Ix_ShowLatestYt')) {

    class Ix_ShowLatestYt {

        static private $_instance = null;
        private $_textdomain = 'ix-show-latest-yt';
        private $_slug = 'ix-show-latest-yt';
        private $options = array(
            'ytid' => 'moritzhangouttv', // the default YouTube ID
            'width' => '611', // the default width for the embeded video
            'height' => '382', // the default height for the embeded video
        );

// BEGIN: General plugin methods
        public function __construct() {
            self::$_instance = $this;
            load_plugin_textdomain($this->_textdomain, false, dirname(plugin_basename(__FILE__)) . '/languages/');
            add_action('plugins_loaded', array($this, 'init'));
        }

        public function init() {
            if (is_admin()) {
                add_action('admin_menu', array($this, 'add_options_page'));
                if (isset($_GET['page']) && $_GET['page'] == $this->_slug) {
                    add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
                    add_action('init', array($this, 'manageRequest'));
                }
            }

            $this->options = get_option($this->_slug, $this->options);
            add_shortcode('ix_show_latest_yt', array($this, 'show_latest'));

            // Enable shortcodes for text widgets
            if (!has_filter('widget_text', 'do_shortcode'))
                add_filter('widget_text', 'do_shortcode', 11); // AFTER wpautop()
        }

        static public function get_instance() {
            if (is_null(self::$_instance))
                new self;

            return self::$_instance;
        }

        public function admin_enqueue_scripts() {
            wp_enqueue_style($this->_slug, plugin_dir_url(__FILE__) . 'admin/options-page.css');
        }

        public function add_options_page() {
            $page = add_options_page('Ixiter Show Latest YouTube ' . __('Options', $this->_textdomain), 'Ixiter Show Latest YT', 'administrator', $this->_slug, array($this, 'options_page'));
        }

        public function manageRequest() {
            $action = $this->_slug . '-options';
            $nonce = $this->_slug . '-nonce';
            if (isset($_POST[$this->_slug . '-submit']) && check_admin_referer($action, $nonce)) {
                foreach ($this->options as $key => $val) {
                    $this->options[$key] = esc_attr($_POST[$key]);
                }
                update_option($this->_slug, $this->options);
                wp_redirect(admin_url() . 'options-general.php?page=' . $this->_slug . '&updated=updated');
            }
        }

        public function options_page() {
            $slug = $this->_slug;
            $textdomain = $this->_textdomain;
            $action = $slug . '-options';
            $nonce = $slug . '-nonce';
            extract($this->options);
            require_once dirname(__FILE__) . '/admin/options-page.phtml';
        }

// END: General plugin methods
//
// BEGIN: Custom plugin methods
        public function show_latest($atts) {
            extract(shortcode_atts(array(
                        'ytid' => $this->options['ytid'],
                        'width' => $this->options['width'],
                        'height' => $this->options['height'],
                            ), $atts)
            );
            $html = '
<script type="text/javascript" src="' . plugin_dir_url(__FILE__) . 'show-latest-yt.js"></script>
<div class="showlatestyt" data-uid="' . $ytid . '" data-width="' . $width . '" data-height="' . $height . '"></div>
';

            return $html;
        }

// END: Custom plugin methods
    }

    Ix_ShowLatestYt::get_instance();

// BEGIN: Template Tags
//
    function ix_show_latest_yt($ytid = '', $width = '', $height = '') {
        $options = Ix_ShowLatestYt::get_instance()->get_options();
        $ytid = is_empty($ytid) ? $options['ytid'] : $ytid;
        $width = is_empty($width) ? $options['width'] : $width;
        $height = is_empty($height) ? $options['height'] : $height;

        echo Ix_ShowLatestYt::get_instance()->show_latest(compact('ytid', 'width', 'height'));
    }

// END: Template Tags
}