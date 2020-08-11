const defaultConfig = require("@wordpress/scripts/config/.eslintrc.js");

module.exports = {
	...defaultConfig,
	globals: {
		...defaultConfig.globals,
		snowmonkeyforms: true,
		FormData: true,
		XMLHttpRequest: true,
	},
};
