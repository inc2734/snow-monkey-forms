import { Popover, IconButton } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function () {
	const [ isVisible, setIsVisible ] = useState( false );

	return (
		<IconButton
			icon="editor-help"
			label={ __( 'Help', 'snow-monkey-forms' ) }
			onClick={ () => setIsVisible( ! isVisible ) }
		>
			{ isVisible && (
				<Popover>
					<ul style={ { margin: 0, padding: '13px' } }>
						<li>
							{ __(
								'You can embed a submitted value in the following formats: ',
								'snow-monkey-forms'
							) }
							<b style={ { color: '#ca4a1f' } }>{ `{` }</b>
							name
							<b style={ { color: '#ca4a1f' } }>{ `}` }</b>
						</li>
						<li>
							{ __(
								'You can embed all submitted values ​​in the following format: ',
								'snow-monkey-forms'
							) }
							<b
								style={ { color: '#ca4a1f' } }
							>{ `{all-fields}` }</b>
						</li>
					</ul>
				</Popover>
			) }
		</IconButton>
	);
}
