<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Setting {
	protected $controls = [];
	protected $complete_message = '';

	public function __construct( $form_id ) {
		$_posts = get_posts(
			[
				'post_type'        => 'snow-monkey-forms',
				'post__in'         => [ $form_id ],
				'posts_per_page'   => 1,
				'suppress_filters' => false,
				'no_found_rows'    => true,
			]
		);

		if ( ! $_posts ) {
			return;
		}

		preg_replace_callback(
			'|<!-- wp:snow-monkey-forms/([^ ]+?) ({.+?}) /-->|ms',
			function( $matches ) {
				if ( ! isset( $matches[1] ) || ! isset( $matches[2] ) ) {
					return;
				}

				$type       = $matches[1];
				$attributes = json_decode( $matches[2], true );
				$control    = Helper::control( $type, Helper::block_meta_normalization( $attributes ) );

				if ( $control ) {
					$this->controls[] = $control;
				}
			},
			$_posts[0]->post_content
		);
	}

	public function get( $key ) {
		if ( isset( $this->$key ) ) {
			return $this->$key;
		}
	}
}
