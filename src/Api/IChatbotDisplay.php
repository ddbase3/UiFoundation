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
 * Shared display contract for replaceable chatbot clients.
 *
 * Implementations receive the complete browser-facing chatbot
 * configuration through setData(). Common configuration keys are:
 *
 * - service_url: resolved chatbot service URL
 * - service: technical chatbot service name
 * - turn_prepare_url: resolved URL for preparing SSE turns
 * - config_group: SettingsStore group of the chatbot instance
 * - config_name: SettingsStore name of the chatbot instance
 * - use_markdown: whether markdown rendering is enabled
 * - use_icons: whether message action icons are enabled
 * - use_voice: whether voice controls are enabled
 * - use_threads: whether thread controls are enabled
 * - transport_mode: auto, sse, or rest
 * - reference_mode: none, url, custom, or provider
 * - reference: optional custom reference payload
 * - reference_provider: optional browser-side reference provider name
 * - default_lang: default language for voice controls
 *
 * Implementations own their templates, browser assets, and client-side
 * initialization. Consumers must depend on this interface instead of a
 * concrete client implementation so projects can replace the active
 * chatbot presentation through dependency injection.
 */
interface IChatbotDisplay extends IDisplay {
}
