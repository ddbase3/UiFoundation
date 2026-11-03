<?php declare(strict_types=1);

namespace UiFoundation\Settings;

use Base3\Translation\Api\ITranslation;
use UiFoundation\Api\ISettingsTab;

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
