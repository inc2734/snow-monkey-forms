const defaultConfig = require("@wordpress/scripts/config/.eslintrc.js");

module.exports = {
	...defaultConfig,
	globals: {
		...defaultConfig.globals,
		snowmonkeyforms: true,
	},
	rules: {
		...defaultConfig.rules,
		'@wordpress/i18n-text-domain': [
			'error',
			{
				allowedTextDomain: 'snow-monkey-forms',
			},
		],
	},
};
