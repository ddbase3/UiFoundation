<?php declare(strict_types=1);

namespace UiFoundation\Test\Settings;

use Base3\Authorization\Api\IAuthorizationService;
use Base3\Authorization\Dto\AuthorizationDecision;
use Base3\Authorization\Dto\AuthorizationRequest;
use PHPUnit\Framework\TestCase;
use UiFoundation\Settings\SettingsAdministrationAccess;

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
