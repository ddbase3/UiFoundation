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

namespace Base3\UiFoundation\Test\Settings;

use Base3\CoreFoundation\Authorization\Api\IAuthorizationService;
use Base3\CoreFoundation\Authorization\Dto\AuthorizationDecision;
use Base3\CoreFoundation\Authorization\Dto\AuthorizationRequest;
use PHPUnit\Framework\TestCase;
use Base3\UiFoundation\Settings\SettingsAdministrationAccess;

final class SettingsAdministrationAccessTest extends TestCase {

	public function testRequiresNativeAdminAndFeatureAllow(): void {
		$authorization = $this->createMock(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willReturn('ilias');
		$authorization->method('decide')->willReturnCallback(static function(AuthorizationRequest $request): AuthorizationDecision {
			return $request->permission->scope === 'system'
				? AuthorizationDecision::allow()
				: AuthorizationDecision::allow();
		});
		self::assertTrue(SettingsAdministrationAccess::isAllowed($authorization, 'iliassettingstab'));
	}

	public function testDeniedNativeAdminShortCircuitsFeature(): void {
		$authorization = $this->createMock(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willReturn('ilias');
		$authorization->expects(self::once())->method('decide')->willReturn(AuthorizationDecision::deny());
		self::assertFalse(SettingsAdministrationAccess::isAllowed($authorization, 'iliasdashboardsettingstab'));
	}

	public function testFeatureDenialIsNotOverriddenByNativeAdmin(): void {
		$authorization = $this->createMock(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willReturn('ilias');
		$authorization->method('decide')->willReturnCallback(static function(AuthorizationRequest $request): AuthorizationDecision {
			return $request->permission->scope === 'system'
				? AuthorizationDecision::allow()
				: AuthorizationDecision::indeterminate();
		});
		self::assertFalse(SettingsAdministrationAccess::isAllowed($authorization, 'iliasdashboardsettingstab'));
	}
}
