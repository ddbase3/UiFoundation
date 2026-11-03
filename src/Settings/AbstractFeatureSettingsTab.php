<?php declare(strict_types=1);

namespace UiFoundation\Settings;

use Base3\Authorization\Api\IAuthorizationService;
use Base3\Authorization\Api\IFeature;
use Base3\Authorization\Dto\AuthorizationRequest;
use Base3\Translation\Api\ITranslation;
use Base3\Usermanager\Permission;
use Throwable;

/**
 * A settings tab and its unique BASE3 feature declaration share one identity.
 * This helper delegates decisions to the existing IAuthorizationService;
 * it neither evaluates audiences nor authorizes the target display's actions.
 */
abstract class AbstractFeatureSettingsTab extends AbstractSettingsTab implements IFeature {

	public function __construct(
		ITranslation $translation,
		private readonly IAuthorizationService $authorization
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
