import config from '../../src/js/config';

export default function () {
	return (
		<svg
			viewBox="0 0 24 24"
			xmlns="http://www.w3.org/2000/svg"
			style={ { color: config.blandColor } }
		>
			<rect
				fill="none"
				x="3.75"
				y="3.75"
				width="16.5"
				height="16.5"
				rx="1.53571"
				stroke="currentColor"
				strokeWidth="1.5"
			/>
			<path
				fill="none"
				d="M16.6232 7.99994L10.6895 15.9801L7.24875 13.4218"
				stroke="currentColor"
				strokeWidth="1.5"
			/>
		</svg>
	);
}
