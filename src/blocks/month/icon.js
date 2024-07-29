import config from '../../../src/js/config';

export default function () {
	return (
		<svg
			viewBox="0 0 24 24"
			xmlns="http://www.w3.org/2000/svg"
			style={ { color: config.blandColor } }
		>
			<path
				clipRule="evenodd"
				d="m22 7.5h-20c-.27614 0-.5.22386-.5.5v8c0 .2761.22386.5.5.5h20c.2761 0 .5-.2239.5-.5v-8c0-.27614-.2239-.5-.5-.5zm-20-1.5c-1.104569 0-2 .89543-2 2v8c0 1.1046.89543 2 2 2h20c1.1046 0 2-.8954 2-2v-8c0-1.10457-.8954-2-2-2z"
				fillRule="evenodd"
			/>
			<rect
				x="13"
				y="9.25"
				width="1"
				height="5"
				transform="rotate(30 13 9.25)"
			/>
			<rect x="6.75" y="11.25" width="3" height="1.5" />
			<rect x="14.25" y="11.25" width="3" height="1.5" />
		</svg>
	);
}
