<?php declare(strict_types=1);

namespace UiFoundation\Api;

use Base3\Api\IBase;

/**
 * A discoverable, uniquely named entry in the two-level BASE3 settings navigation.
 *
 * The first level contains only groups; the second contains displays. A group
 * without visible child tabs is not rendered. This interface describes navigation
 * and never authorizes the operations executed by the referenced display.
 */
interface ISettingsTab extends IBase {

	/**
	 * Returns the translated, non-empty navigation and feature label.
	 *
	 * @return string Safe human-readable label in the current language
	 */
	public function getLabel(): string;

	/**
	 * Returns the translated tooltip and accessible description.
	 *
	 * @return string Plain-text description; may be empty if not applicable
	 */
	public function getDescription(): string;

	/**
	 * Returns the stable name of the first-level group containing this tab.
	 *
	 * Root tabs return null. Child tabs must reference an existing root
	 * ISettingsTab::getName(); nested grandchildren are not supported.
	 *
	 * @return string|null Parent root name, or null for a root tab
	 */
	public function getParentTabName(): ?string;

	/**
	 * Returns the technical IDisplay::getName() to render for a child tab.
	 *
	 * Root tabs must return null; child tabs must return a non-empty name.
	 * Display resolution belongs to the UI renderer, not the tab definition.
	 *
	 * @return string|null Display name, or null for a root tab
	 */
	public function getDisplayName(): ?string;

	/**
	 * Returns trusted static configuration passed to IDisplay::setData().
	 *
	 * Root tabs must return null. The data must not contain untrusted client
	 * authorization context; every rendered endpoint independently authorizes
	 * its own operations and any data identifiers supplied by a request.
	 *
	 * @return mixed Display configuration or null when none is needed
	 */
	public function getDisplayData(): mixed;

	/**
	 * Determines whether this tab may be shown to the current actor.
	 *
	 * Child tabs must consult IAuthorizationService for their feature
	 * permission; only an explicit ALLOW returns true. Implementations must
	 * fail closed on missing services, denied or indeterminate decisions.
	 * This navigation check never grants permission to invoke a display.
	 *
	 * @return bool True only when navigation is permitted for this actor
	 */
	public function isVisible(): bool;

	/**
	 * Returns the relative position among siblings, ascending.
	 *
	 * Equal positions are resolved by lexicographic technical tab name,
	 * independently within root tabs and each root's child tabs.
	 *
	 * @return int Deterministic sibling order, including negative values
	 */
	public function getOrder(): int;
}
