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

namespace Base3\UiFoundation\Settings;

use Base3\CoreFoundation\Authorization\Api\IAuthorizationService;
use Base3\CoreFoundation\Authorization\Dto\AuthorizationRequest;
use Base3\CoreFoundation\Usermanager\Permission;
use Throwable;

/** Checks a protected settings display through the existing authorization authority. */
final class SettingsAdministrationAccess {

	/**
	 * Requires both the discoverable feature grant and the native system administrator decision.
	 * A missing, denied or indeterminate decision never authorizes access.
	 *
	 * @param IAuthorizationService $authorization The owning host authorization service.
	 * @param string $featureName Name of the discoverable IFeature protecting the operation.
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
