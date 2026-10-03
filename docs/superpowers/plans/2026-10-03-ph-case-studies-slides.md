# PH Case Studies Slides Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Make Case Studies slide copy editable via a Slides repeater per `docs/superpowers/specs/2026-10-03-ph-case-studies-slides-design.md`.

**Architecture:** Repeater → `data.php` payload → JSON in markup → JS carousel. Header/chip/aria/fact labels stay content-mapped.

**Tech Stack:** Elementor Repeater, PHP payload, `nexora-ph-cases.js`, existing PH runtime.

### Task 1: data.php + controls (Slides repeater)
### Task 2: markup/render/content-map (JSON bridge, drop singleton CTA)
### Task 3: JS + version bump + verify
