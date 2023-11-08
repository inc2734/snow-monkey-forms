const defaultConfig = require("@wordpress/scripts/config/.eslintrc.js");

module.exports = {
	...defaultConfig,
	globals: {
		...defaultConfig.globals,
		snowmonkeyforms: true,
		snowmonkeyforms_recaptcha: true,
		grecaptcha: true,
		FormData: true,
		XMLHttpRequest: true,
	},
	rules: {
		...defaultConfig.rules,
		'import/no-extraneous-dependencies': 'off',
		'import/no-unresolved': 'off',
		'@wordpress/no-unsafe-wp-apis': 'off',
		'jsx-a11y/label-has-associated-control': 'off',
	},
};
