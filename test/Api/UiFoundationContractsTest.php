<?php declare(strict_types=1);

namespace UiFoundation\Test\Api;

use Base3\Api\IBase;
use Base3\Api\IDisplay;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UiFoundation\Api\IAdminDisplay;
use UiFoundation\Api\IAuthorizationAudienceAdministrationService;
use UiFoundation\Api\IChatbotDisplay;
use UiFoundation\Api\IRichTextEditorDisplay;
use UiFoundation\Api\ISettingsTab;

final class UiFoundationContractsTest extends TestCase {

	public function testUiPresentationContractsRemainReplaceableDisplayInterfaces(): void {
		foreach ([IAdminDisplay::class, IChatbotDisplay::class, IRichTextEditorDisplay::class] as $contract) {
			$this->assertTrue(interface_exists($contract));
			$this->assertTrue(is_subclass_of($contract, IDisplay::class));
		}
	}

	public function testSettingsTabIsDiscoverableAndHasMetadataAndVisibilityOperations(): void {
		$this->assertTrue(is_subclass_of(ISettingsTab::class, IBase::class));
		foreach (['getLabel', 'getDescription', 'getParentTabName', 'getDisplayName', 'getDisplayData', 'isVisible', 'getOrder'] as $method) {
			$this->assertTrue((new ReflectionClass(ISettingsTab::class))->hasMethod($method));
		}
	}

	public function testAudienceAdministrationPortSeparatesReadWriteAndAuthorizationOperations(): void {
		$contract = new ReflectionClass(IAuthorizationAudienceAdministrationService::class);
		$this->assertTrue($contract->isInterface());
		foreach (['assertCanAdminister', 'getGroupView', 'replaceGroupRules', 'createGroup', 'removeGroup', 'getFeatureView', 'replaceFeatureRules'] as $method) {
			$this->assertTrue($contract->hasMethod($method));
		}
		$this->assertSame('void', (string)$contract->getMethod('assertCanAdminister')->getReturnType());
		$this->assertSame('void', (string)$contract->getMethod('replaceGroupRules')->getReturnType());
		$this->assertSame('void', (string)$contract->getMethod('replaceFeatureRules')->getReturnType());
	}
}
