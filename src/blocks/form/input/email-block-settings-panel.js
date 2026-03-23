import {
	BaseControl,
	Button,
	PanelBody,
	TextControl,
} from '@wordpress/components';

import { useState } from '@wordpress/element';
import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const parseBlockedSenders = ( value ) => {
	if ( ! value ) {
		return [];
	}

	try {
		const parsedValue = JSON.parse( value );
		if ( ! Array.isArray( parsedValue ) ) {
			return [];
		}
		return parsedValue
			.filter( ( item ) => 'string' === typeof item )
			.map( ( item ) => item.trim() )
			.filter( Boolean );
	} catch ( error ) {
		return [];
	}
};

const normalizeBlockedSender = ( value ) => value.trim().toLowerCase();

export default function () {
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		'snow-monkey-forms',
		'meta'
	);

	const [ blockedSenderSource, setblockedSenderSource ] = useState( '' );

	const blockedSenders = parseBlockedSenders( meta.blocked_sender_list );

	const normalizedblockedSenderSource =
		normalizeBlockedSender( blockedSenderSource );

	const canAddBlockedSender =
		!! normalizedblockedSenderSource &&
		! blockedSenders.some(
			( blockedSender ) =>
				normalizeBlockedSender( blockedSender ) ===
				normalizedblockedSenderSource
		);

	const onAddBlockedSender = () => {
		if ( ! canAddBlockedSender ) {
			return;
		}

		setMeta( {
			...meta,
			blocked_sender_list: JSON.stringify( [
				...blockedSenders,
				normalizedblockedSenderSource,
			] ),
		} );
		setblockedSenderSource( '' );
	};

	const onRemoveBlockedSender = ( senderToRemove ) => {
		const filteredBlockedSenders = blockedSenders.filter(
			( blockedSender ) =>
				normalizeBlockedSender( blockedSender ) !==
				normalizeBlockedSender( senderToRemove )
		);

		setMeta( {
			...meta,
			blocked_sender_list: JSON.stringify( filteredBlockedSenders ),
		} );
	};

	return (
		<PanelBody
			title={ __( 'Blocked senders', 'snow-monkey-forms' ) }
			initialOpen={ false }
		>
			<TextControl
				__next40pxDefaultSize
				__nextHasNoMarginBottom
				label={ __( 'Target email', 'snow-monkey-forms' ) }
				help={ __(
					'Enter the name attribute value of the installed email form field in the following format: {name}',
					'snow-monkey-forms'
				) }
				value={ meta.blocked_sender_source || '' }
				onChange={ ( value ) =>
					setMeta( { ...meta, blocked_sender_source: value } )
				}
			/>

			<BaseControl
				__nextHasNoMarginBottom
				id="smf-blocked-sender-source-input"
				label={ __(
					'Blocked email address or domain',
					'snow-monkey-forms'
				) }
				help={ __(
					'If no "@" is included, the value is treated as a domain.',
					'snow-monkey-forms'
				) }
			>
				<div
					style={ {
						display: 'flex',
						gap: '8px',
						alignItems: 'center',
					} }
				>
					<div style={ { flex: '1 1 auto' } }>
						<TextControl
							id="smf-blocked-sender-source-input"
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							value={ blockedSenderSource }
							onChange={ setblockedSenderSource }
							onKeyDown={ ( event ) => {
								if ( 'Enter' === event.key ) {
									event.preventDefault();
									onAddBlockedSender();
								}
							} }
						/>
					</div>

					<Button
						variant="secondary"
						onClick={ onAddBlockedSender }
						disabled={ ! canAddBlockedSender }
					>
						{ __( 'Add', 'snow-monkey-forms' ) }
					</Button>
				</div>
			</BaseControl>

			{ 0 < blockedSenders.length && (
				<ul
					style={ {
						display: 'grid',
						gridTemplateColumns: '1fr auto',
						gap: '1px',
					} }
				>
					{ blockedSenders.map( ( blockedSender ) => (
						<li
							key={ blockedSender }
							style={ {
								display: 'grid',
								gridTemplateColumns: 'subgrid',
								alignItems: 'center',
								gridColumn: 'span 2',
								outline: '1px solid #ddd',
								margin: '0',
								padding: '6px 10px',
							} }
						>
							<span>{ blockedSender }</span>
							<Button
								isDestructive
								variant="link"
								onClick={ () =>
									onRemoveBlockedSender( blockedSender )
								}
							>
								{ __( 'Delete', 'snow-monkey-forms' ) }
							</Button>
						</li>
					) ) }
				</ul>
			) }
		</PanelBody>
	);
}
