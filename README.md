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

