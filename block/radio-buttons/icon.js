import config from '../../src/js/config';

export default function () {
	return (
		<svg
			viewBox="0 0 24 24"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			style={ { color: config.blandColor } }
		>
			<circle cx="12" cy="12" r="3" fill="currentColor" />
			<circle
				cx="12"
				cy="12"
				r="8"
				fill="none"
				stroke="currentColor"
				strokeWidth="1.5"
			/>
		</svg>
	);
}
