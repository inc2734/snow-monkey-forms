export default {
	name: {
		type: 'string',
		default: '',
	},
	value: {
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
	description: {
		type: 'string',
		default: '',
	},
	validations: {
		type: 'string',
		default: '{}',
	},
};
