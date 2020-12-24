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
	protected $administrator_email_from;

	/**
	 * @var string
	 */
	protected $administrator_email_sender;

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

	/**
	 * @var string
	 */
	protected $auto_reply_email_from;

	/**
	 * @var string
	 */
	protected $auto_reply_email_sender;

	/**
	 * @var boolean
	 */
	protected $use_confirm_page = true;

	/**
	 * @var string
	 */
	protected $confirm_button_label = null;

	/**
	 * @var string
	 */
	protected $back_button_label = null;

	/**
	 * @var string
	 */
	protected $send_button_label = null;

	/**
	 * Construct.
	 *
	 * @param int $form_id The post ID (ID of form editing page).
	 */
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
		$this->administrator_email_from    = get_post_meta( $form_id, 'administrator_email_from', true );
		$this->administrator_email_sender  = get_post_meta( $form_id, 'administrator_email_sender', true );

		$this->auto_reply_email_to      = get_post_meta( $form_id, 'auto_reply_email_to', true );
		$this->auto_reply_email_subject = get_post_meta( $form_id, 'auto_reply_email_subject', true );
		$this->auto_reply_email_body    = get_post_meta( $form_id, 'auto_reply_email_body', true );
		$this->auto_reply_email_from    = get_post_meta( $form_id, 'auto_reply_email_from', true );
		$this->auto_reply_email_sender  = get_post_meta( $form_id, 'auto_reply_email_sender', true );

		$use_confirm_page       = get_post_meta( $form_id, 'use_confirm_page', true );
		$this->use_confirm_page = ! $use_confirm_page ? false : true;

		$use_progress_tracker       = get_post_meta( $form_id, 'use_progress_tracker', true );
		$this->use_progress_tracker = ! $use_progress_tracker ? false : true;

		$confirm_button_label       = get_post_meta( $form_id, 'confirm_button_label', true );
		$this->confirm_button_label = $confirm_button_label
			? $confirm_button_label
			: __( 'Confirm', 'snow-monkey-forms' );

		$back_button_label       = get_post_meta( $form_id, 'back_button_label', true );
		$this->back_button_label = $back_button_label
			? $back_button_label
			: __( 'Back', 'snow-monkey-forms' );

		$send_button_label       = get_post_meta( $form_id, 'send_button_label', true );
		$this->send_button_label = $send_button_label
			? $send_button_label
			: __( 'Send', 'snow-monkey-forms' );
	}

	/**
	 * Return setting.
	 *
	 * @param string $key The setting name.
	 * @return mixed
	 */
	public function get( $key ) {
		$properties = array_keys( get_object_vars( $this ) );
		if ( in_array( $key, $properties, true ) ) {
			return $this->$key;
		}
	}

	/**
	 * Set system error message.
	 *
	 * @param string $message System error message.
	 */
	public function set_system_error_message( $message ) {
		$this->system_error_messages[] = $message;
	}

	/**
	 * Return the control.
	 *
	 * @param string $name The form field name.
	 * @return \Snow_Monkey\Plugin\Forms\App\Contract\Control
	 */
	public function get_control( $name ) {
		return isset( $this->controls[ $name ] ) ? $this->controls[ $name ] : false;
	}

	/**
	 * Return controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		return $this->controls;
	}

	/**
	 * Extract input content.
	 *
	 * @param string $post_content The post (post_content of form editing page) content.
	 * @return string
	 */
	private function _extract_input_content( $post_content ) {
		$match = preg_match(
			'|<!-- wp:snow-monkey-forms/form--input .*?-->(.*?)<!-- /wp:snow-monkey-forms/form--input -->|ms',
			$post_content,
			$matches
		);

		return $match ? $matches[1] : null;
	}

	/**
	 * Extract complete content.
	 *
	 * @param string $post_content The post (post_content of form editing page) content.
	 * @return string
	 */
	private function _extract_complete_content( $post_content ) {
		$match = preg_match(
			'|<!-- wp:snow-monkey-forms/form--complete -->(.*?)<!-- /wp:snow-monkey-forms/form--complete -->|ms',
			$post_content,
			$matches
		);

		return $match ? $matches[1] : null;
	}

	/**
	 * Set the form controls to $this->controls.
	 *
	 * @param string $input_content The input page content.
	 */
	private function _set_controls( $input_content ) {
		preg_replace_callback(
			'|<!-- wp:snow-monkey-forms/control-([^ ]+?) ({.+?}) /-->|ms',
			function( $matches ) {
				if ( ! isset( $matches[1] ) || ! isset( $matches[2] ) ) {
					return;
				}

				$type       = $matches[1];
				$attributes = json_decode( $matches[2], true );
				$name       = ! empty( $attributes['name'] ) ? $attributes['name'] : null;
				$control    = Helper::control( $type, Helper::block_meta_normalization( $attributes ) );

				if ( $control && $name ) {
					$this->controls[ $name ] = $control;
				}
			},
			$input_content
		);
	}
}
