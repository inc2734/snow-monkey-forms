const defaultConfig = require( '@wordpress/scripts/config/eslint.config.cjs' );

module.exports = [
	{
		ignores: [
			'**/dist/**',
			'**/node_modules/**',
			'**/vendor/**',
			'postcss.config.js',
			'webpack.config.js',
		],
	},
	...defaultConfig,
	{
		files: [ 'hoc/**/*.js', 'src/**/*.js' ],
		languageOptions: {
			globals: {
				FormData: 'readonly',
				XMLHttpRequest: 'readonly',
				grecaptcha: 'readonly',
				snowmonkeyforms: 'readonly',
				snowmonkeyforms_recaptcha: 'readonly',
			},
		},
		rules: {
			'import/no-extraneous-dependencies': 'off',
			'import/no-unresolved': [
				'error',
				{
					ignore: [ '^@wordpress/' ],
				},
			],
			'@wordpress/no-unsafe-wp-apis': 'off',
			'jsx-a11y/label-has-associated-control': 'off',
		},
	},
];
