import config from '../../src/js/config';

export default function () {
	return (
		<svg
			viewBox="0 0 24 24"
			xmlns="http://www.w3.org/2000/svg"
			style={ { color: config.blandColor } }
		>
			<path
				d="m6.30769 12.75h-2.30769v-1.5h2.3077zm3.23077 0h-2.30769v-1.5h2.3077zm3.23074 0h-2.3077v-1.5h2.3077zm-10.7692-5.25c-.27614 0-.5.22386-.5.5v8c0 .2761.22386.5.5.5h20c.2761 0 .5-.2239.5-.5v-8c0-.27614-.2239-.5-.5-.5zm0-1.5c-1.104569 0-2 .89543-2 2v8c0 1.1046.89543 2 2 2h20c1.1046 0 2-.8954 2-2v-8c0-1.10457-.8954-2-2-2z"
				clipRule="evenodd"
				fillRule="evenodd"
			/>
		</svg>
	);
}
