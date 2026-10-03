# PH GTM Impact Tabs Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Make Impact tab copy editable via a Tabs repeater (Pricing pattern) per `docs/superpowers/specs/2026-10-03-ph-gtm-impact-tabs-design.md`.

**Architecture:** Repeater controls → `data.php` payload → JSON in markup → JS tab switcher. Header/aria stay content-mapped.

**Tech Stack:** Elementor Repeater, PHP payload, `nexora-ph-impact.js`, existing PH runtime.

### Task 1: data.php + controls (Tabs repeater)
### Task 2: markup/render/content-map (JSON bridge, drop singleton image)
### Task 3: JS + version bump + verify
