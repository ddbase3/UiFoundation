# UiFoundation

UiFoundation is a lightweight foundation layer for user interface semantics and contracts.

It provides stable, framework-agnostic interfaces and shared UI concepts that can be reused across projects without coupling to a specific framework, rendering engine, or asset pipeline.

## Purpose

The goal of UiFoundation is to define *what* a UI component is expected to provide, not *how* it is rendered or delivered. It serves as a common contract layer between application logic, UI implementations, and integration layers.

UiFoundation intentionally contains **no concrete UI implementations**, **no templates**, and **no assets**.

## Typical Use Cases

* Defining admin or configuration displays
* Describing UI-related capabilities via interfaces
* Sharing UI-oriented value objects and enums
* Providing common UI semantics across plugins or applications

## What Belongs Here

* Interfaces (e.g. admin displays, widgets, status indicators)
* UI-related value objects
* Small, framework-neutral helper abstractions

## What Does NOT Belong Here

* Framework-specific code (e.g. ILIAS GUI classes)
* HTML templates
* CSS or JavaScript assets
* Asset loading or build logic
* Business or domain logic

## Design Principles

* Framework-agnostic
* Stable and minimal APIs
* Clear separation of concerns
* No hidden side effects

UiFoundation is intended to be a long-lived, low-level dependency that higher-level UI layers can safely build upon.


## Chatbot display contract

`UiFoundation\Api\IChatbotDisplay` defines the replaceable browser presentation slot for chatbot clients. Chatbot domain plugins prepare the browser-facing configuration and delegate rendering through this interface. UI implementation plugins own their templates, assets, and client initialization.

## Documentation

- [Frequently Asked Questions](docs/faq.md)
- [Privacy and data processing](PRIVACY.md)

## Authorization audience administration backend

`IAuthorizationAudienceAdministrationService` is an optional UI-facing port for the two reusable ClientStack administration displays. It does not define a new authorization authority or a second rights store. Each host owns data, validates rows, authorizes actual actions and persists its own groups and feature audience expressions.

## Discoverable settings tabs

`UiFoundation\Api\ISettingsTab` declares navigation contributions, discovered using
`IClassMap`. Root entries return `null` for their parent and display. Child entries
reference a root via `getParentTabName()` and a real `IDisplay::getName()`.
`getDisplayData()` supplies trusted static display configuration; `getOrder()`
sorts siblings. `getLabel()` and `getDescription()` return translated text.

`UiFoundation\Settings\AbstractSettingsTab` supplies optional shared metadata and
translation behavior. `AbstractFeatureSettingsTab` additionally implements
`IFeature`; its permission is `feature/<tab getName()>`, with default group
`administration`, and `isVisible()` evaluates the existing
`IAuthorizationService`. An implementation can override its declared audience,
for example for a user-owned credentials page. Neither type implements an ACL,
changes native permissions, or grants access to the underlying display.

Every concrete tab, feature declaration, and visible translation belongs to the
plugin offering that setting. A contributed tab must be enforced by the display
and all of its action endpoints as well as the navigation.

## Feature authorization reuse

The shared feature-enabled Settings base makes its injected `IAuthorizationService` available to specialized subclasses through a protected readonly property. This allows an individual tab to require additional existing permissions without implementing a parallel authorization service. No new feature policy or storage responsibility is introduced in UiFoundation.


## Protected administration entries

`AbstractAdministrationSettingsTab` extends the existing feature-backed tab contract and requires an explicit native `system/admin` decision as well as its own feature grant. `SettingsAdministrationAccess` is a small reusable caller of the existing `IAuthorizationService`, not an additional authorization authority, storage or policy evaluator. Displays must check access independently of navigation.
