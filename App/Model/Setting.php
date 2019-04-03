<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Model;

class Setting {
	protected $form_id;
	protected $controls = [];
	protected $complete_message = '';

	public function __construct( $form_id ) {
		$this->form_id = $form_id;

		// @todo 本当はここでデータベースから情報を取得して設定する
		if ( 1 == $form_id ) {
			$this->controls = [
				[
					'name'    => 'お名前',
					'type'    => 'text',
					'label'   => 'お名前 ※',
					'require' => true,
				],
				[
					'name'  => 'ご住所',
					'type'  => 'text',
					'label' => 'ご住所',
				],
				[
					'name'  => '趣味',
					'type'  => 'checkbox',
					'label' => '趣味',
					'children' => [
						'soccer'   => 'サッカー',
						'baseball' => '野球',
						'tennis'   => 'テニス',
					],
				],
			];

			$this->complete_message = '送信しました！';
		} else if ( 2 == $form_id ) {
			$this->controls = [
				[
					'name'    => 'お名前',
					'type'    => 'text',
					'label'   => 'お名前 ※',
					'require' => true,
				],
			];

			$this->complete_message = '送信したぜ！';
		}
	}

	public function get( $key ) {
		if ( isset( $this->$key ) ) {
			return $this->$key;
		}
	}
}
