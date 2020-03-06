export default {
	name: {
		type: 'string',
		default: '',
	},
	values: {
		type: 'string',
		default: '',
	},
	disabled: {
		type: 'boolean',
		default: false,
	},
	options: {
		type: 'string',
		default: `value1
"value2" : "label2"
"value3" : "label3"`,
	},
	delimiter: {
		type: 'string',
		default: ', ',
	},
	description: {
		type: 'string',
		default: '',
	},
	validations: {
		type: 'string',
		default: '{}',
	},
};
