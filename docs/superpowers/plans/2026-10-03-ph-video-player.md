# PH Video Player Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add Self Hosted / YouTube / Vimeo player controls with poster to PH Video per `docs/superpowers/specs/2026-10-03-ph-video-player-design.md`.

**Architecture:** New Content section controls; PHP builds a `[data-ph-video]` player shell that replaces mock content when a source exists; JS handles play → native video or iframe embed.

**Tech Stack:** Elementor controls, PHP render injection, vanilla JS, existing `nexora-ph-video.css`.

### Task 1: Controls + registry script
### Task 2: Markup/render player HTML
### Task 3: JS + CSS + version bump
