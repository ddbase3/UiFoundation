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
use Base3\CoreFoundation\Authorization\Api\IFeature;
use Base3\CoreFoundation\Authorization\Dto\AuthorizationRequest;
use Base3\CoreFoundation\Translation\Api\ITranslation;
use Base3\CoreFoundation\Usermanager\Permission;
use Throwable;

/**
 * A settings tab and its unique BASE3 feature declaration share one identity.
 * This helper delegates decisions to the existing IAuthorizationService;
 * it neither evaluates audiences nor authorizes the target display's actions.
 */
abstract class AbstractFeatureSettingsTab extends AbstractSettingsTab implements IFeature {

	public function __construct(
		ITranslation $translation,
		protected readonly IAuthorizationService $authorization
	) {
		parent::__construct($translation);
	}

	public function getPermissions(): array {
		return [Permission::for('feature', static::getName())];
	}

	public function getDefaultAudience(): array {
		return ['group' => 'administration'];
	}

	public function isVisible(): bool {
		try {
			return $this->authorization->decide(new AuthorizationRequest(
				$this->authorization->getDefaultAuthority(),
				Permission::for('feature', static::getName())
			))->isAllowed();
		} catch (Throwable) {
			return false;
		}
	}
}
