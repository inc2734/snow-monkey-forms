import config from '../../src/js/config';

export default function () {
	return (
		<svg
			viewBox="0 0 24 24"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			style={ { color: config.blandColor } }
		>
			<path
				d="M7.5 8.5L12 4.5L16.5 8.5"
				fill="none"
				stroke="currentColor"
				strokeWidth="1.5"
			/>
			<path
				d="M16.5 15.5L12 19.5L7.5 15.5"
				fill="none"
				stroke="currentColor"
				strokeWidth="1.5"
			/>
		</svg>
	);
}
