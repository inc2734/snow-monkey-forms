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
			'|<!-- wp:snow-monkey-forms/([^ ]+?) ({[^}]*?}).?/-->|ms',
			function( $matches ) {
				$control = Helper::control( $matches[1], json_decode( $matches[2], JSON_OBJECT_AS_ARRAY ) );
				if ( $control ) {
					$this->controls[] = $control;
				}
			},
			$_posts[0]->post_content
		);

		// @todo 本当はここでデータベースから情報を取得して設定する
		/*
		$this->controls = [
			[
				'type'       => 'text',
				'label'      => 'お名前 ※',
				'attributes' => [
					'name' => 'お名前',
				],
				'validations' => [
					'required' => true,
				],
			],
			[
				'type'       => 'text',
				'label'      => 'ご住所',
				'attributes' => [
					'name' => 'ご住所',
				],
			],
			[
				'type'       => 'multi-checkbox',
				'label'      => '趣味 ※',
				'attributes' => [
					'name'  => '趣味',
					'children' => [
						[
							'label'      => 'サッカー',
							'attributes' => [
								'value' => 'soccer',
							],
						],
						[
							'label'      => '野球',
							'attributes' => [
								'value' => 'baseball',
							],
						],
						[
							'label'      => 'テニス',
							'attributes' => [
								'value' => 'tennis',
							],
						],
					],
				],
				'validations' => [
					'required' => true,
				],
			],
		];

		$this->complete_message = '送信しました！';
		*/
	}

	public function get( $key ) {
		if ( isset( $this->$key ) ) {
			return $this->$key;
		}
	}
}
