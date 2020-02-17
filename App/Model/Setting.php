<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

use Snow_Monkey\Plugin\Forms\App\Helper;

class Setting {

	/**
	 * @var array
	 */
	protected $controls = [];

	/**
	 * @var array
	 */
	protected $system_error_messages = [];

	/**
	 * @var string
	 */
	protected $input_content;

	/**
	 * @var string
	 */
	protected $complete_content;

	/**
	 * @var string
	 */
	protected $administrator_email_to;

	/**
	 * @var string
	 */
	protected $administrator_email_subject;

	/**
	 * @var string
	 */
	protected $administrator_email_body;

	/**
	 * @var string
	 */
	protected $auto_reply_email_to;

	/**
	 * @var string
	 */
	protected $auto_reply_email_subject;

	/**
	 * @var string
	 */
	protected $auto_reply_email_body;

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

		$this->input_content    = $this->_extract_input_content( $_posts[0]->post_content );
		$this->complete_content = $this->_extract_complete_content( $_posts[0]->post_content );

		if ( ! $this->input_content ) {
			return;
		}

		$this->_set_controls( $this->input_content );

		$this->administrator_email_to      = get_post_meta( $form_id, 'administrator_email_to', true );
		$this->administrator_email_subject = get_post_meta( $form_id, 'administrator_email_subject', true );
		$this->administrator_email_body    = get_post_meta( $form_id, 'administrator_email_body', true );

		$this->auto_reply_email_to      = get_post_meta( $form_id, 'auto_reply_email_to', true );
		$this->auto_reply_email_subject = get_post_meta( $form_id, 'auto_reply_email_subject', true );
		$this->auto_reply_email_body    = get_post_meta( $form_id, 'auto_reply_email_body', true );
	}

	public function get( $key ) {
		$properties = array_keys( get_object_vars( $this ) );
		if ( in_array( $key, $properties ) ) {
			return $this->$key;
		}
	}

	public function set_system_error_message( $message ) {
		$this->system_error_messages[] = $message;
	}

	private function _extract_input_content( $post_content ) {
		$match = preg_match(
			'|<!-- wp:snow-monkey-forms/form--input -->(.*?)<!-- /wp:snow-monkey-forms/form--input -->|ms',
			$post_content,
			$matches
		);

		return $match ? $matches[1] : null;
	}

	private function _extract_complete_content( $post_content ) {
		$match = preg_match(
			'|<!-- wp:snow-monkey-forms/form--complete -->(.*?)<!-- /wp:snow-monkey-forms/form--complete -->|ms',
			$post_content,
			$matches
		);

		return $match ? $matches[1] : null;
	}

	private function _set_controls( $input_content ) {
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
			$input_content
		);
	}
}
