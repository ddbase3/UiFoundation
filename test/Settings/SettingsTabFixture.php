<?php declare(strict_types=1);

namespace UiFoundation\Test\Settings;

use UiFoundation\Settings\AbstractSettingsTab;

final class SettingsTabFixture extends AbstractSettingsTab {

	public function getOrder(): int {
		return -7;
	}
}
