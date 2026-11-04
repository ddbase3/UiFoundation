<?php declare(strict_types=1);

namespace UiFoundation\Test;

use Base3\Api\IContainer;
use Base3\Api\IPlugin;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UiFoundation\UiFoundationPlugin;

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
