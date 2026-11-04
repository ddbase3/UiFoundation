<?php declare(strict_types=1);

namespace UiFoundation\Test\Settings;

use UiFoundation\Settings\AbstractFeatureSettingsTab;

final class FeatureSettingsTabFixture extends AbstractFeatureSettingsTab {

	public function getOrder(): int {
		return 25;
	}
}
