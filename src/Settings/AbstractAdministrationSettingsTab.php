<?php declare(strict_types=1);

namespace UiFoundation\Settings;

/** Discoverable settings entry requiring the feature audience and native host administration. */
abstract class AbstractAdministrationSettingsTab extends AbstractFeatureSettingsTab {

	public function getDefaultAudience(): array {
		return ['group' => 'development'];
	}

	public function isVisible(): bool {
		return SettingsAdministrationAccess::isAllowed($this->authorization, static::getName());
	}
}
