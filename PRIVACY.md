# Privacy and Data Processing in UiFoundation

This document describes the privacy-relevant behavior of the UiFoundation component itself. UiFoundation is a contract-only user-interface foundation. It does not contain concrete templates, browser code, assets, persistence, authentication, or network transport.

Concrete display implementations and consuming applications can process personal data through these contracts and must document that processing separately.

## Component scope

UiFoundation currently defines:

- `IAdminDisplay`
- `IChatbotDisplay`
- `IRichTextEditorDisplay`
- the minimal `UiFoundationPlugin` registration class

All three display interfaces extend the BASE3 `IDisplay` concept. UiFoundation does not provide the classes that actually render the interfaces.

## No direct persistence

UiFoundation contains no database migrations, storage services, state stores, settings stores, file repositories, caches, or browser storage code.

Installing the component does not by itself persist:

- display configuration
- editor content
- chatbot references
- service URLs
- form values
- user identifiers
- browser state

Persistence begins only when another component stores data supplied through or produced by a concrete display implementation.

## Admin display contract

`IAdminDisplay` is a marker interface for administration-oriented displays.

It does not carry data-processing methods beyond the inherited display contract and does not implement authentication or authorization.

A concrete admin display can expose configuration, user data, operational state, or other sensitive information. The host application must ensure that such displays are protected at the appropriate server-side access boundary.

The presence of the `IAdminDisplay` interface must not be treated as proof that a page is access-controlled.

## Chatbot display configuration

`IChatbotDisplay` documents browser-facing configuration keys that can include:

- service URLs
- a technical chatbot service name
- turn-preparation URL
- settings group and settings name
- feature flags for markdown, icons, voice, and threads
- transport mode
- reference mode
- a custom reference value
- a reference-provider name
- default language

Some of these values can be privacy-relevant or security-relevant depending on how a consuming application populates them.

For example, a custom `reference` value can contain an object identifier, page context, document reference, user-related context, or another application-defined payload. UiFoundation does not define the contents and does not minimize or sanitize it.

## Chatbot network behavior

UiFoundation itself does not send requests to `service_url` or `turn_prepare_url` because it contains no browser client.

A concrete `IChatbotDisplay` implementation can expose these values to browser code that performs network requests. That implementation must document:

- which endpoints are contacted
- which request data is transmitted
- whether conversation or context data is sent
- authentication behavior
- browser storage
- logging
- retention
- third-party processing, if any

## Chatbot feature flags

Options such as `use_voice` or `use_threads` describe UI capabilities only. They do not cause speech data, conversation history, or thread data to be processed by UiFoundation.

Actual processing depends on the concrete chatbot display and the services behind it.

## Rich-text editor values

`IRichTextEditorDisplay` can receive an initial `value` for the editor. That value can contain arbitrary text and markup, including personal, confidential, or sensitive information.

UiFoundation does not store, parse, sanitize, transform, transmit, or log the value. A concrete editor implementation and the server-side form handler are responsible for the content lifecycle.

## Canonical textarea

The rich-text editor contract requires a canonical textarea identified by the configured `id`. A client editor can enhance or visually replace the textarea, but it must keep the value synchronized.

This means editor content can be present in the browser DOM even when a richer client widget is shown. Browser-side scripts with access to the page can potentially read that value according to the normal browser security model.

The contract does not introduce any additional browser isolation mechanism.

## Optional editor adapter

A concrete rich-text implementation can expose a `base3RichTextEditor` adapter on the textarea with methods such as `getValue()` and `setValue()`.

UiFoundation does not implement the adapter and does not determine whether content is additionally copied into JavaScript state. That behavior belongs to the concrete editor implementation.

## Readonly and disabled fields

The interface documents `readonly` and `disabled` options as presentation and form-control semantics.

These flags are not security boundaries. A server must still validate submitted data and enforce authorization independently of how the control was rendered in the browser.

## No content sanitization

UiFoundation does not sanitize rich text, markdown, chatbot content, HTML, URLs, or reference data.

Concrete renderers must apply appropriate escaping and sanitization for their output context. Server-side consumers must validate submitted or generated content before storing or rendering it where active markup could be interpreted.

## No authentication or authorization

UiFoundation does not implement:

- login
- sessions
- cookies
- user lookup
- roles
- permissions
- CSRF protection
- endpoint authorization

A UI may hide or disable a control, but visibility or disabled state must never replace server-side permission enforcement.

## No automatic browser storage

UiFoundation contains no JavaScript and therefore does not use:

- Local Storage
- Session Storage
- IndexedDB
- browser cookies
- Cache Storage

A concrete UI implementation can use these mechanisms, in which case its own privacy documentation must describe what is stored and for how long.

## No automatic network communication

The package contains no HTTP client and no browser code. Merely installing UiFoundation does not transmit data to remote systems.

Network processing begins only in concrete implementations or consuming applications.

## No logging by UiFoundation

UiFoundation does not register or use a logger. It does not automatically record:

- editor content
- display configuration
- service URLs
- chatbot references
- language choices
- form field identifiers

Concrete displays and request handlers may log such values and should assess whether those logs can contain personal or confidential information.

## Accessibility-related values

`IRichTextEditorDisplay` can receive `aria_label`, placeholder text, CSS class names, and element identifiers.

These values are normally presentation metadata, but applications should still avoid embedding unnecessary personal information into DOM identifiers, class names, labels, or other UI metadata that can be exposed to browser tools and client-side code.

## Retention and deletion

UiFoundation defines no retention periods because it owns no persistent data.

Retention and deletion policies belong to the components that store data submitted or displayed through concrete implementations, including:

- rich-text content
- chatbot conversations and references
- configuration records
- browser-side persistence
- server logs
- uploaded or generated files
- backups

Removing a UI element does not delete data previously stored by the underlying application.

## Responsibilities of concrete UI implementations

Implementations of UiFoundation contracts should document and test, as applicable:

- output escaping and content sanitization
- server-side authorization of related operations
- CSRF protection for state-changing requests
- browser storage
- network endpoints and request payloads
- third-party frontend libraries
- CDN or external asset requests
- cookies and session interaction
- handling of editor content
- handling of chatbot reference values
- logging
- accessibility behavior
- retention and deletion of any persisted UI state

UiFoundation provides stable UI contracts only. It does not replace the privacy, security, and accessibility responsibilities of the concrete implementation or host application.
