<?php declare(strict_types=1);

namespace UiFoundation\Api;

use RuntimeException;

/** Host-owned data and mutations for the reusable BASE3 Groups and Features administration UIs. */
interface IAuthorizationAudienceAdministrationService {

	/**
	 * Reject access unless the trusted actor may administer BASE3 groups and feature audiences.
	 * Hosts may impose additional requirements, e.g. the ILIAS development group.
	 * The UI also independently verifies system/admin through IAuthorizationService.
	 * @return void
	 * @throws RuntimeException If the actor is not authorized or the backend is unavailable
	 * @sideeffects None; no rights are granted by this check
	 */
	public function assertCanAdminister(): void;

	/**
	 * Read the effective BASE3 group catalog and metadata needed by the shared editor.
	 * Must preserve fixed code-owned criteria separately from editable host criteria.
	 * @return array{entries:list<array>,defaultGroup:string,criterionOptions:list<array>,roleOptions:list<array>,roleHint:string}
	 * @throws RuntimeException If the catalog cannot be read or the actor is unauthorized
	 * @sideeffects None
	 */
	public function getGroupView(): array;

	/**
	 * Replace the editable membership expressions, preserving fixed code-defined memberships.
	 * Submitted rows are untrusted; validation and atomic persistence belong to the host.
	 * @param array<string,array> $rows Rows indexed by existing editable group names
	 * @param string $defaultGroup Optional nonprivileged fallback group name
	 * @return void
	 * @throws \InvalidArgumentException On malformed, unknown or protected groups and rules
	 * @throws RuntimeException On unauthorized or failed persistence
	 * @sideeffects Replaces only host-managed group membership expressions and fallback
	 */
	public function replaceGroupRules(array $rows, string $defaultGroup): void;

	/**
	 * Create a host-managed BASE3 group DTO, never a PHP class nor a native host role.
	 * @param string $name Unique stable technical name
	 * @param string $description Nonempty human-readable description
	 * @return void
	 * @throws \InvalidArgumentException On invalid or duplicate definitions
	 * @throws RuntimeException On unauthorized or failed persistence
	 * @sideeffects Creates one host-managed logical BASE3 group
	 */
	public function createGroup(string $name, string $description): void;

	/**
	 * Remove one host-managed group only when it is not protected or referenced.
	 * Fixed code groups and referenced feature audiences must remain intact.
	 * @param string $name Existing host-managed group name
	 * @return void
	 * @throws \InvalidArgumentException If the group is missing, protected or referenced
	 * @throws RuntimeException On unauthorized or failed persistence
	 * @sideeffects Removes one editable host-managed group
	 */
	public function removeGroup(string $name): void;

	/**
	 * Read the discovered feature catalog and effective audience rules for editing.
	 * Features must originate from the owning authority, not from an unrelated frontend.
	 * @return array{rows:list<array>,groups:list<string>,criterionOptions:list<array>,roleOptions:list<array>,roleHint:string}
	 * @throws RuntimeException On unauthorized access or unavailable backend
	 * @sideeffects None
	 */
	public function getFeatureView(): array;

	/**
	 * Replace editable feature audience overrides using the same criterion grammar as groups.
	 * A stored empty expression is explicit denial; no permission may be inferred from UI visibility.
	 * @param array<string,array> $rows Posted audience rows keyed by discovered feature name
	 * @return void
	 * @throws \InvalidArgumentException On malformed/unknown features or expressions
	 * @throws RuntimeException On unauthorized or failed persistence
	 * @sideeffects Replaces the owning host's persisted feature audience overrides
	 */
	public function replaceFeatureRules(array $rows): void;
}
