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

namespace Base3\UiFoundation\Test;

use Base3\CoreFoundation\Api\IContainer;
use Base3\CoreFoundation\Api\IPlugin;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Base3\UiFoundation\UiFoundationPlugin;

final class UiFoundationPluginTest extends TestCase {

	public function testPluginHasStableNameAndImplementsFrameworkContract(): void {
		$container = $this->createStub(IContainer::class);
		$plugin = new UiFoundationPlugin($container);

		$this->assertInstanceOf(IPlugin::class, $plugin);
		$this->assertSame('uifoundationplugin', UiFoundationPlugin::getName());
	}

	public function testInitRegistersSamePluginInstanceAsSharedService(): void {
		$container = $this->createStub(IContainer::class);
		$registrations = [];
		$container->method('set')->willReturnCallback(
			static function(string $name, mixed $definition, int $flags = 0) use (&$registrations, $container): IContainer {
				$registrations[] = [$name, $definition, $flags];
				return $container;
			}
		);
		$plugin = new UiFoundationPlugin($container);

		$this->assertNull($plugin->init());
		$this->assertSame([['uifoundationplugin', $plugin, IContainer::SHARED]], $registrations);
	}

	public function testInitDoesNotMaskContainerRegistrationErrors(): void {
		$container = $this->createStub(IContainer::class);
		$container->method('set')->willThrowException(new RuntimeException('Registration failed'));
		$plugin = new UiFoundationPlugin($container);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Registration failed');
		$plugin->init();
	}
}
