<?php declare(strict_types=1);

namespace UiFoundation\Test\Settings;

use Base3\Translation\Api\ITranslation;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UiFoundation\Api\ISettingsTab;

final class AbstractSettingsTabTest extends TestCase {

	public function testDefaultMetadataDescribesVisibleRootTabWithoutDisplay(): void {
		$tab = new SettingsTabFixture($this->createStub(ITranslation::class));

		$this->assertInstanceOf(ISettingsTab::class, $tab);
		$this->assertSame('settingstabfixture', SettingsTabFixture::getName());
		$this->assertSame(-7, $tab->getOrder());
		$this->assertNull($tab->getParentTabName());
		$this->assertNull($tab->getDisplayName());
		$this->assertNull($tab->getDisplayData());
		$this->assertTrue($tab->isVisible());
	}

	public function testLabelAndDescriptionUseOwningTranslationSection(): void {
		$translation = $this->createStub(ITranslation::class);
		$calls = [];
		$translation->method('translate')->willReturnCallback(
			static function(string $set, string $section, string $key, string $fallback = '', array $replacements = []) use (&$calls): string {
				$calls[] = [$set, $section, $key, $fallback, $replacements];
				return 'Translated ' . $key;
			}
		);
		$tab = new SettingsTabFixture($translation);

		$this->assertSame('Translated label', $tab->getLabel());
		$this->assertSame('Translated description', $tab->getDescription());
		$this->assertSame([
			['Settings', 'settingstabfixture', 'label', '', []],
			['Settings', 'settingstabfixture', 'description', '', []]
		], $calls);
	}

	public function testTranslationErrorsAreNotSilentlyHidden(): void {
		$translation = $this->createStub(ITranslation::class);
		$translation->method('translate')->willThrowException(new RuntimeException('Translation unavailable'));
		$tab = new SettingsTabFixture($translation);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Translation unavailable');
		$tab->getLabel();
	}

	public function testDescriptionUsesTranslationServiceWithoutInventingFallback(): void {
		$translation = $this->createStub(ITranslation::class);
		$translation->method('translate')->willReturn('');
		$tab = new SettingsTabFixture($translation);

		$this->assertSame('', $tab->getDescription());
	}
}

require_once __DIR__ . '/SettingsTabFixture.php';
