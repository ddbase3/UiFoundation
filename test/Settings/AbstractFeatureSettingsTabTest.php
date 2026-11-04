<?php declare(strict_types=1);

namespace UiFoundation\Test\Settings;

use Base3\Authorization\Api\IAuthorizationService;
use Base3\Authorization\Api\IFeature;
use Base3\Authorization\Dto\AuthorizationDecision;
use Base3\Authorization\Dto\AuthorizationRequest;
use Base3\Translation\Api\ITranslation;
use Base3\Usermanager\Permission;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UiFoundation\Api\ISettingsTab;

final class AbstractFeatureSettingsTabTest extends TestCase {

	public function testFeatureDeclaresOneStablePermissionAndAdministrationAudience(): void {
		$tab = $this->tab($this->createStub(IAuthorizationService::class));

		$this->assertInstanceOf(IFeature::class, $tab);
		$this->assertInstanceOf(ISettingsTab::class, $tab);
		$this->assertSame('featuresettingstabfixture', FeatureSettingsTabFixture::getName());
		$this->assertSame(['group' => 'administration'], $tab->getDefaultAudience());
		$this->assertCount(1, $tab->getPermissions());
		$permission = $tab->getPermissions()[0];
		$this->assertInstanceOf(Permission::class, $permission);
		$this->assertSame('feature', $permission->scope);
		$this->assertSame('featuresettingstabfixture', $permission->permission);
	}

	public function testOnlyExplicitAllowMakesTabVisibleForCurrentActor(): void {
		$authorization = $this->createStub(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willReturn('ilias:installation-1');
		$requests = [];
		$authorization->method('decide')->willReturnCallback(
			static function(AuthorizationRequest $request) use (&$requests): AuthorizationDecision {
				$requests[] = $request;
				return AuthorizationDecision::allow();
			}
		);
		$tab = $this->tab($authorization);

		$this->assertTrue($tab->isVisible());
		$this->assertCount(1, $requests);
		$this->assertSame('ilias:installation-1', $requests[0]->authority);
		$this->assertSame('feature', $requests[0]->permission->scope);
		$this->assertSame('featuresettingstabfixture', $requests[0]->permission->permission);
		$this->assertNull($requests[0]->resource);
		$this->assertNull($requests[0]->subject);
		$this->assertNull($requests[0]->onBehalfOf);
		$this->assertSame([], $requests[0]->parameters);
		$this->assertSame([], $requests[0]->context);
	}

	public function testDenyAndIndeterminateNeverExposeFeature(): void {
		foreach ([AuthorizationDecision::deny(), AuthorizationDecision::indeterminate()] as $decision) {
			$authorization = $this->createStub(IAuthorizationService::class);
			$authorization->method('getDefaultAuthority')->willReturn('ilias:installation-1');
			$authorization->method('decide')->willReturn($decision);

			$this->assertFalse($this->tab($authorization)->isVisible());
		}
	}

	public function testAuthorityLookupFailureDeniesVisibility(): void {
		$authorization = $this->createStub(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willThrowException(new RuntimeException('Host unavailable'));
		$authorization->method('decide')->willReturnCallback(
			static function(): never { throw new RuntimeException('Decision must not be reached'); }
		);

		$this->assertFalse($this->tab($authorization)->isVisible());
	}

	public function testDecisionServiceFailureDeniesVisibility(): void {
		$authorization = $this->createStub(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willReturn('ilias:installation-1');
		$authorization->method('decide')->willThrowException(new RuntimeException('Native authorization unavailable'));

		$this->assertFalse($this->tab($authorization)->isVisible());
	}

	public function testMissingAuthorityIsNotTreatedAsImplicitAllow(): void {
		$authorization = $this->createStub(IAuthorizationService::class);
		$authorization->method('getDefaultAuthority')->willReturn(' ');
		$decisions = 0;
		$authorization->method('decide')->willReturnCallback(
			static function(AuthorizationRequest $request) use (&$decisions): AuthorizationDecision {
				$decisions++;
				return AuthorizationDecision::allow();
			}
		);

		$this->assertFalse($this->tab($authorization)->isVisible());
		$this->assertSame(0, $decisions);
	}

	public function testFeatureInheritsRootMetadataAndUsesItsOwnTranslationKeys(): void {
		$translation = $this->createStub(ITranslation::class);
		$calls = [];
		$translation->method('translate')->willReturnCallback(
			static function(string $set, string $section, string $key) use (&$calls): string {
				$calls[] = [$set, $section, $key];
				return $key;
			}
		);
		$tab = new FeatureSettingsTabFixture($translation, $this->createStub(IAuthorizationService::class));

		$this->assertSame('label', $tab->getLabel());
		$this->assertSame('description', $tab->getDescription());
		$this->assertSame([
			['Settings', 'featuresettingstabfixture', 'label'],
			['Settings', 'featuresettingstabfixture', 'description']
		], $calls);
		$this->assertNull($tab->getParentTabName());
		$this->assertNull($tab->getDisplayName());
		$this->assertNull($tab->getDisplayData());
		$this->assertSame(25, $tab->getOrder());
	}

	private function tab(IAuthorizationService $authorization): FeatureSettingsTabFixture {
		return new FeatureSettingsTabFixture($this->createStub(ITranslation::class), $authorization);
	}
}

require_once __DIR__ . '/FeatureSettingsTabFixture.php';
