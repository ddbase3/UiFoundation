<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of UiFoundation for BASE3 Framework.
 *
 * UiFoundation extends the BASE3 framework with a shared API
 * foundation for user interface semantics and contracts.
 * It provides reusable, framework-agnostic UI interfaces.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de/v/uifoundation
 * https://github.com/ddbase3/UiFoundation
 **********************************************************************/

namespace UiFoundation\Api;

use Base3\Api\IDisplay;

/**
 * Shared display contract for replaceable rich text editor controls.
 *
 * Implementations receive their configuration through setData(). Common
 * configuration keys are:
 *
 * - id: DOM id of the canonical textarea control
 * - name: optional form field name
 * - value: initial editor content
 * - class: optional additional CSS classes
 * - rows: fallback textarea row count
 * - placeholder: optional placeholder text
 * - spellcheck: whether browser spell checking is enabled
 * - readonly: whether editing is disabled while the value remains submitted
 * - disabled: whether the control is disabled
 * - aria_label: optional accessible label
 *
 * The rendered output must contain a canonical textarea with the configured
 * id. Rich client implementations may visually replace or enhance this
 * textarea, but must keep its value synchronized so consumers can use the
 * control without depending on a specific editor library.
 *
 * Client implementations may additionally expose an adapter object through
 * textarea.base3RichTextEditor with getValue(), setValue(), focus(), and
 * destroy() methods. Consumers must continue to support the native textarea
 * value as the fallback contract.
 */
interface IRichTextEditorDisplay extends IDisplay {
}
