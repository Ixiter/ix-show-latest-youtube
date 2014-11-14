<?php

/**
 * Ix_ShowLatestYt is a WordPress plugin that provides a shortcode to embed the latest video or the current live video from one or more YouTube channels into your post or page.
 *
 * @package Ixiter WordPress Plugins
 * @subpackage IX Show Latest YouTube
 * @version 2.4.1
 * @author Peter Liebetrau <ixiter@ixiter.com>
 * @license GPL 3 or greater
 */
if (!class_exists('Ix_ShowLatestYt')) {

	class Ix_ShowLatestYt {

		static private $_instance = null;
		private $_textdomain = 'ix-show-latest-yt';
		private $_slug = 'ix-show-latest-yt';
		private $default_options = array(
			'ytid' => 'moritzhangouttv,theaudacitytopodcast', // the default YouTube ID
			'width' => '611', // the default width for the embeded video
			'height' => '382', // the default height for the embeded video
			'autoplay' => '0', // no autoplay by default
			'count_of_videos' => '1', // Embed one latest video by default
			'no_live_message' => '', // displayed when no live broadcasting available, when set.
			'related' => '0'// hide related videos by default
		);
		private $options = array();

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

			$this->options = get_option($this->_slug, $this->default_options);
// add keys from defaults if not exists
			foreach ($this->default_options as $key => $value) {
				if (!array_key_exists($key, $this->options)) {
					$this->options[$key] = $value;
				}
			}
			$this->save_options($this->_slug, $this->options);
			add_shortcode('ix_show_latest_yt', array($this, 'show_latest'));

// Enable shortcodes for text widgets
			if (!has_filter('widget_text', 'do_shortcode')) {
				add_filter('widget_text', 'do_shortcode', 11);
			}
			// AFTER wpautop()
		}

		static public function get_instance() {
			if (is_null(self::$_instance)) {
				new self;
			}

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
				$this->save_options($this->_slug, $this->options);
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

		public function get_options() {
			return $this->options;
		}

		private function save_options() {
			$this->options['count_of_videos'] = (int) $this->options['count_of_videos'] < 1 ? '1' : $this->options['count_of_videos'];
			update_option($this->_slug, $this->options);
		}

// END: General plugin methods
		//
		// BEGIN: Custom plugin methods

		public function show_latest($atts) {

			// Function to accept either active or pending items in a feed
			function liveFeedUrl($ytid, $status) {
				return 'https://gdata.youtube.com/feeds/api/users/' . $ytid . '/live/events?v=2&alt=json&status=' . $status;
			}

			function feed($ytids, $count) {
				foreach ($ytids as $ytid) {
					$feedSource = json_decode(wp_remote_fopen('http://gdata.youtube.com/feeds/users/' . $ytid . '/uploads?alt=json&max-results=' . $count));
					$feedEntries[] = $feedSource->feed->entry;
				}
				$feedCount = count($ytids) - 1;
				if ($feedCount > 0) {
					$i = 0;
					while ($feedCount > 0) {
						$feed->feed->entry = array_merge_recursive($feedEntries[$i], $feedEntries[++$i]);
						$feedCount--;
					}
				} else {
					$feed = $feedSource;
				}

				// Sort by date and mixing channels

				$ytwhen = '$t';
				foreach ($feed->feed->entry as $key => $row) {
					$volume[$key] = $row->published->$ytwhen;
				}
				array_multisort($volume, SORT_DESC, $feed->feed->entry);
				return $feed;
			}

			extract(shortcode_atts(array(
				'ytid' => $this->options['ytid'],
				'width' => $this->options['width'],
				'height' => $this->options['height'],
				'autoplay' => $this->options['autoplay'],
				'count_of_videos' => $this->options['count_of_videos'],
				'no_live_message' => $this->options['no_live_message'],
				'related' => $this->options['related'],
			), $atts)
			);
			$html = '';
			$t = '$t';
			// Split multiple IDs into an array
			str_replace(' ', '', $ytid);
			$ytids = explode(',', $ytid);
			// Combine multiple channel feeds into one
			foreach ($ytids as $ytid) {
				$feedActiveSource = json_decode(wp_remote_fopen(liveFeedUrl($ytid, 'active')));
				if (isset($feedActiveSource->feed->entry)) {
					$feedActiveEntries[] = $feedActiveSource->feed->entry;
				}
				$feedPendingSource = json_decode(wp_remote_fopen(liveFeedUrl($ytid, 'pending')));
				if (isset($feedPendingSource->feed->entry)) {
					$feedPendingEntries[] = $feedPendingSource->feed->entry;
				}
			}
			$feedPendingCount = count($ytids) - 1;
			if ($feedPendingCount > 0) {
				$i = 0;
				while ($feedPendingCount > 0) {
					$feedPending->feed->entry = array_merge_recursive($feedPendingEntries[$i], $feedPendingEntries[++$i]);
					$feedPendingCount--;
				}
				$feedActiveCount = count($ytids) - 1;
				$i = 0;
				while ($feedActiveCount > 0) {
					$feedActive->feed->entry = array_merge_recursive($feedActiveEntries[$i], $feedActiveEntries[++$i]);
					$feedActiveCount--;
				}
			} else {
				$feedActive = $feedActiveSource;
				$feedPending = $feedPendingSource;
			}

			// Check for active and pending live video
			// If there is no active video, display pending
			if (isset($feedActive->feed->entry)) {
				$feed = $feedActive;
			} else {
				$feed = $feedPending;
			}
			if (isset($feed->feed->entry)) {
				// Revised to get soonest start date
				$entryCount = count($feed->feed->entry);
				$ytwhen = 'yt$when';
				foreach ($feed->feed->entry as $key => $row) {
					$volume[$key] = $row->$ytwhen->start;
				}
				array_multisort($volume, SORT_ASC, $feed->feed->entry);

				// We have a live video!
				$videoFeedUrl = $feed->feed->entry[0]->content->src;
				$uriParts = explode('/', $videoFeedUrl);
				$videoIdParts = explode('?', $uriParts[count($uriParts) - 1]);
				$videoId = $videoIdParts[0];
				$html .= $this->embedIframe($videoId, $width, $height, $autoplay, $related);
				// embed some more videos ?
				if ($count_of_videos > 1) {
					$count = ($count_of_videos - 1);
					$feed = feed($ytids, $count);
					if (isset($feed->feed->entry)) {
						$loop = 0;
						foreach ($feed->feed->entry as $key => $value) {
							if ($loop <= $count) {
								$videoFeedUrl = $value->id->$t;
								$uriParts = explode('/', $videoFeedUrl);
								$videoId = $uriParts[count($uriParts) - 1];
								$html .= $this->embedIframe($videoId, $width, $height, false, $related);
								$loop++;
							}
						}
					}
				}
			} else {
				if ($this->options['no_live_message'] == '') {
					$count = $count_of_videos;
					$feed = feed($ytids, $count);
					//ix_dump($feed);
					if (isset($feed->feed->entry)) {
						$loop = 0;
						foreach ($feed->feed->entry as $key => $value) {
							if ($loop <= ($count - 1)) {
								$autoplay = $loop > 0 ? false : $autoplay;
								$loop++;
								$videoFeedUrl = $value->id->$t;
								$uriParts = explode('/', $videoFeedUrl);
								$videoId = $uriParts[count($uriParts) - 1];
								$html .= $this->embedIframe($videoId, $width, $height, $autoplay, $related);
							}
						}
					} else {
						$html .= sprintf(__('No more videos found for channel %s'), $this->options['ytid']);
					}
				} else {
					$html .= '
                        <div style="width:' . $width . '; height:' . $height . '; background-color:black;">
                            <span style="display:block; width:80%; margin:0 auto; padding-top:50px; color:#f0f0f0; text-align:center;">' . $no_live_message . '</span>
                        </div>
                    ';
				}
			}

			return $html;
		}

		private function embedIframe($videoId, $width, $height, $autoplay, $related) {
			$src = 'http://www.youtube.com/embed/';
			$autoplay = $this->is_true(strtolower($autoplay)) ? 'autoplay=1' : 'autoplay=0';
			$related = $this->is_true(strtolower($related)) ? 'rel=1' : 'rel=0';
			$parameters = '?';
			$parameters .= $autoplay;
			$parameters .= '&';// if autoplay and related are both enabled, add &
			$parameters .= $related;// add related if it exists
			$src .= $videoId . $parameters;
			$html = '<iframe src="' . $src . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowfullscreen="true"></iframe>';

			return $html;
		}

// END: Custom plugin methods
		// BEGIN: Ixiter tools and methods

		/**
		 *
		 * @param mixed $expression
		 */
		public function is_true($expression) {
			$trues = array('true', '1', 'on', 'yes');
			if (is_bool($expression)) {
				$result = $expression;
			} else {
				$result = is_array($expression) || in_array((string) $expression, $trues);
			}
			return $result;
		}

	}

	Ix_ShowLatestYt::get_instance();

// BEGIN: Template Tags

	function ix_show_latest_yt($ytid = '', $width = '', $height = '', $autoplay = '', $count_of_videos = '', $no_live_message = '', $related = '') {
		$options = Ix_ShowLatestYt::get_instance()->get_options();
		$ytid = empty($ytid) ? $options['ytid'] : $ytid;
		$width = empty($width) ? $options['width'] : $width;
		$height = empty($height) ? $options['height'] : $height;
		$autoplay = empty($autoplay) ? $options['autoplay'] : $autoplay;
		$count_of_videos = empty($count_of_videos) ? $options['count_of_videos'] : $count_of_videos;
		$related = empty($related) ? $options['related'] : $related;

		echo Ix_ShowLatestYt::get_instance()->show_latest(compact('ytid', 'width', 'height', 'autoplay', 'count_of_videos', 'no_live_message', 'related'));
	}

// END: Template Tags
}
