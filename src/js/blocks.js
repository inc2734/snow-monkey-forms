'use strict';

/**
 * Add validations controls
 */

import { withInspectorControls } from '../../block/hooks/validations';
wp.hooks.addFilter( 'editor.BlockEdit', 'snow-monkey-forms/withInspectorControls/validations', withInspectorControls );

/**
 * Import blocks
 */

import '../../block/text/block';
import '../../block/checkbox/block';
