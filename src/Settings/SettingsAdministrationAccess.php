<?php declare(strict_types=1);

namespace UiFoundation\Settings;

use Base3\Authorization\Api\IAuthorizationService;
use Base3\Authorization\Dto\AuthorizationRequest;
use Base3\Usermanager\Permission;
use Throwable;

/** Checks a protected settings display through the existing authorization authority. */
final class SettingsAdministrationAccess {

	/**
	 * Requires both the discoverable feature grant and the native system administrator decision.
	 * A missing, denied or indeterminate decision never authorizes access.
	 *
	 * @param IAuthorizationService $authorization The owning host authorization service.
	 * @param string $featureName Name of the IFeature-backed ISettingsTab.
	 * @return bool True only if both permissions are explicitly allowed.
	 */
	public static function isAllowed(IAuthorizationService $authorization, string $featureName): bool {
		try {
			$authority = $authorization->getDefaultAuthority();
			return $authorization->decide(new AuthorizationRequest(
				$authority, Permission::for('system', 'admin')
			))->isAllowed() && $authorization->decide(new AuthorizationRequest(
				$authority, Permission::for('feature', $featureName)
			))->isAllowed();
		} catch (Throwable) {
			return false;
		}
	}
}
