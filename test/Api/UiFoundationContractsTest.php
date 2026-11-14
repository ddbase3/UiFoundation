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

namespace Base3\UiFoundation\Test\Api;

use Base3\CoreFoundation\Api\IBase;
use Base3\CoreFoundation\Api\IDisplay;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Base3\UiFoundation\Api\IAdminDisplay;
use Base3\UiFoundation\Api\IAuthorizationAudienceAdministrationService;
use Base3\UiFoundation\Api\IChatbotDisplay;
use Base3\UiFoundation\Api\IRichTextEditorDisplay;
use Base3\UiFoundation\Api\ISettingsTab;

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
