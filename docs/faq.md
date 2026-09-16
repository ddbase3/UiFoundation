# UiFoundation FAQ

## What is UiFoundation?

UiFoundation is the shared BASE3 contract layer for user-interface semantics. It defines stable interfaces that application and domain code can depend on without selecting a concrete template engine, JavaScript library, asset bundle, or host-specific UI implementation.

The current package is intentionally small and contains three display contracts:

- `IAdminDisplay`
- `IChatbotDisplay`
- `IRichTextEditorDisplay`

## Does UiFoundation render HTML itself?

No. UiFoundation contains no templates, CSS, JavaScript, browser assets, or concrete display implementations.

It defines what a compatible display is expected to provide. Another component supplies the actual rendering.

## Does UiFoundation register a default UI implementation?

No. `UiFoundationPlugin` registers only its own plugin instance in the BASE3 container.

It does not bind a concrete admin display, chatbot display, or rich-text editor display.

## What is `IAdminDisplay`?

`IAdminDisplay` is a marker interface extending the BASE3 `IDisplay` contract.

It provides a stable type for displays intended for administration or configuration contexts without prescribing their visual design, routing, permissions, or rendering technology.

## Does `IAdminDisplay` provide admin authorization?

No. The name expresses UI semantics only.

A class implementing `IAdminDisplay` is not automatically protected by a permission check. Authentication and authorization must be enforced by the host application, route, controller, middleware, or concrete display implementation as appropriate.

## What is `IChatbotDisplay`?

`IChatbotDisplay` is the replaceable browser-presentation contract for chatbot clients.

A consumer can prepare browser-facing chatbot configuration and pass it to a concrete display through the standard `IDisplay` data mechanism. The UI implementation owns its templates, assets, and client-side initialization.

This allows application logic to depend on the interface rather than a particular chatbot frontend.

## Which chatbot configuration keys are documented by the contract?

The interface documents common keys including:

- `service_url`
- `service`
- `turn_prepare_url`
- `config_group`
- `config_name`
- `use_markdown`
- `use_icons`
- `use_voice`
- `use_threads`
- `transport_mode`
- `reference_mode`
- `reference`
- `reference_provider`
- `default_lang`

The concrete implementation decides how those values are rendered and used in the browser.

## Does `IChatbotDisplay` implement chatbot conversations or AI processing?

No. It is only a UI contract.

It does not implement conversations, language-model calls, speech processing, streaming, persistence, retrieval, tool execution, or network transport.

Those behaviors belong to the components that prepare or consume the browser-facing configuration.

## What is `IRichTextEditorDisplay`?

`IRichTextEditorDisplay` is the shared contract for replaceable rich-text editor controls.

The contract allows consumers to configure an editor without depending on a specific browser editor library.

## Which rich-text editor configuration keys are documented?

The interface documents common keys including:

- `id`
- `name`
- `value`
- `class`
- `rows`
- `placeholder`
- `spellcheck`
- `readonly`
- `disabled`
- `aria_label`

A concrete implementation can support additional options, but consumers should rely on the shared contract for portable behavior.

## Why must a rich-text editor keep a canonical textarea?

The `IRichTextEditorDisplay` contract requires the rendered output to contain a textarea with the configured `id`.

A rich client can visually replace or enhance that textarea, but it must keep the textarea value synchronized. This preserves a stable form-control boundary even when the active editor implementation changes.

## Is there a client-side editor adapter contract?

The interface documents an optional convention where a concrete implementation can expose an adapter through:

`textarea.base3RichTextEditor`

The adapter may provide:

- `getValue()`
- `setValue()`
- `focus()`
- `destroy()`

Consumers must still support the native textarea value as the fallback contract.

## Does UiFoundation store editor content?

No. UiFoundation does not persist the `value` supplied to an editor and contains no database, file store, browser storage logic, or settings backend.

A concrete editor, form handler, or application decides what happens to submitted content.

## Does UiFoundation load frontend libraries?

No. The package contains no asset resolver calls, script loaders, CDN references, JavaScript dependencies, or CSS bundles.

Concrete UI implementations own their client libraries and asset-loading strategy.

## Does UiFoundation define visual styling?

No. It does not define colors, layout, typography, component markup, themes, responsive behavior, or accessibility styling.

It only defines shared display roles and behavioral expectations that are useful across implementations.

## Does UiFoundation depend on a specific rendering framework?

No. The contracts extend BASE3 display semantics, but they do not require a specific template system, JavaScript framework, rich-text editor, or browser component library.

## Does UiFoundation make network requests?

No. It contains no HTTP client or browser implementation.

Some data described by `IChatbotDisplay`, such as service URLs, is intended for use by a concrete client, but UiFoundation itself does not contact those URLs.

## Does UiFoundation use cookies or browser storage?

No. There is no concrete browser code in the component.

A UI implementation can choose to use cookies, Local Storage, Session Storage, IndexedDB, or no browser persistence at all. Such behavior must be documented by that implementation, not by UiFoundation.

## Does UiFoundation log UI data?

No. The component contains no logger and does not automatically log display configuration, chatbot references, editor content, or form values.

## Does UiFoundation provide CSRF protection?

No. CSRF protection belongs to the request-handling and form-processing boundary of the concrete application.

A display interface alone cannot determine whether a rendered form or endpoint requires CSRF protection.

## Does UiFoundation sanitize HTML or rich-text content?

No. It does not parse, render, sanitize, or escape content.

A concrete rich-text editor and the server-side consumer of submitted rich text must apply the required validation and output-safety policy.

## Does UiFoundation define user permissions?

No. UiFoundation does not manage identities, roles, permissions, or authorization decisions.

UI visibility should not be treated as an authorization boundary. Server-side operations still require their own access enforcement.

## Can concrete implementations be replaced?

Yes. Replaceability is the main purpose of these interfaces.

Application code can depend on `IAdminDisplay`, `IChatbotDisplay`, or `IRichTextEditorDisplay`, while project composition selects the concrete implementation appropriate to the deployment.

## What does the plugin class do during initialization?

`UiFoundationPlugin` registers its own plugin instance under the technical name `uifoundationplugin` in the BASE3 container.

It performs no rendering, asset loading, browser initialization, persistence, or network activity during `init()`.
