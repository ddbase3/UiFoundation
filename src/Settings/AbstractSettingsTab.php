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

namespace Base3\UiFoundation\Settings;

use Base3\CoreFoundation\Translation\Api\ITranslation;
use Base3\UiFoundation\Api\ISettingsTab;

/** Base behavior for plugin-owned, classmap-discoverable settings declarations. */
abstract class AbstractSettingsTab implements ISettingsTab {

	public function __construct(protected readonly ITranslation $translation) {}

	public static function getName(): string {
		$class = static::class;
		$separator = strrpos($class, '\\');
		return strtolower($separator === false ? $class : substr($class, $separator + 1));
	}

	public function getLabel(): string {
		return $this->translation->translate('Settings', static::getName(), 'label');
	}

	public function getDescription(): string {
		return $this->translation->translate('Settings', static::getName(), 'description');
	}

	public function getParentTabName(): ?string {
		return null;
	}

	public function getDisplayName(): ?string {
		return null;
	}

	public function getDisplayData(): mixed {
		return null;
	}

	public function isVisible(): bool {
		return true;
	}

	abstract public function getOrder(): int;
}
