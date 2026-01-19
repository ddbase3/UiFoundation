<?php declare(strict_types=1);

namespace UiFoundation;

use Base3\Api\IContainer;
use Base3\Api\IPlugin;

class UiFoundationPlugin implements IPlugin {

	public function __construct(private readonly IContainer $container) {}

	// Implementation of IBase

	public static function getName(): string {
		return 'uifoundationplugin';
	}

	// Implementation of IPlugin

	public function init() {
		$this->container
			->set(self::getName(), $this, IContainer::SHARED);
	}
}
