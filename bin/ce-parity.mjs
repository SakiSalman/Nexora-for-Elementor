/* 0.3: compare the rendered CE widgets on a QA page against the golden template slices.
   Usage: node bin/ce-parity.mjs <url|file.html> [--widget ce-hero] [--verbose]
   Normalizes what the plugin adds on purpose: the .nexora-ph wrapper, image URL base,
   id suffixes (+ data-ph-anchor), the faq group name, the dynamic <template> wrapper and
   the JSON data script, and (always) phase-3 var(--ph-*, fallback) back to the fallback. */

import fs from 'fs'
import { SECTIONS, readLines, sliceSection, buildImageMap, rewriteAssets, DYNAMIC_CLASSES } from './ce-lib.mjs'

const args = process.argv.slice(2)
const source = args.find((a) => !a.startsWith('--'))
const only = (() => {
  const i = args.indexOf('--widget')
  return i === -1 ? null : args[i + 1]
})()
const verbose = args.includes('--verbose')

if (!source) {
  console.error('usage: node bin/ce-parity.mjs <url|file.html> [--widget ce-<name>] [--verbose]')
  process.exit(2)
}

const load = async (src) => {
  if (/^https?:\/\//i.test(src)) {
    const res = await fetch(src)
    if (!res.ok) throw new Error('HTTP ' + res.status)
    return await res.text()
  }
  return fs.readFileSync(src, 'utf8')
}

const findRoots = (html, slug) => {
  const roots = []
  const open = new RegExp('<div class="nexora-ph nexora-ph-' + slug + '"[^>]*>', 'g')
  let m
  while ((m = open.exec(html))) {
    const start = m.index + m[0].length
    const tag = /<div\b[^>]*>|<\/div>/gi
    tag.lastIndex = start
    let depth = 0
    let end = html.length
    let t
    while ((t = tag.exec(html))) {
      if (t[0].toLowerCase().startsWith('</')) {
        depth -= 1
        // the root's own close is the first </div> that takes us below its level
        if (depth < 0) {
          end = t.index
          break
        }
      } else {
        depth += 1
      }
    }
    roots.push(html.slice(start, end))
  }
  return roots
}

const unwrapTemplate = (text) => {
  const m = text.match(/^\s*<template class="nexora-ph-tpl">([\s\S]*)<\/template>\s*$/)
  return m ? m[1] : text
}

const stripJsonScripts = (text) =>
  text.replace(/<script type="application\/json" class="ph-ce-[\w-]+-data">[\s\S]*?<\/script>/g, '')

const revertImages = (text) =>
  text.replace(/(?:[a-z][\w+.-]*:\/\/[^\/"\s]+)?(?:\/[\w.@%+-]+)*\/wp-content\/plugins\/nexora-for-elementor\/assets\/images\/prospects\//g, 'assets/')

const revertIds = (text) => {
  let out = text
  const pairs = []
  const re = /\sid="([^"]+)" data-ph-anchor="([^"]+)"/g
  let m
  while ((m = re.exec(text))) pairs.push([m[1], m[2]])
  for (const [suffixed, original] of pairs) {
    if (suffixed === original) continue
    out = out.split('"' + suffixed + '"').join('"' + original + '"')
    out = out.split('#' + suffixed).join('#' + original)
  }
  return out.replace(/\sdata-ph-anchor="[^"]+"/g, '')
}

const revertFaq = (text) => text.replace(/name="faq-[A-Za-z0-9_-]+"/g, 'name="faq"')

/* wp_filter_content_tags() adds decoding="async" to every img on the front end */
const revertDecoding = (text) => text.replace(/\sdecoding="async"/g, '')

/* phase 3 writes curated colors as var(--ph-…, fallback); the fallback is the design value */
const revertVars = (text) =>
  text.replace(/var\(\s*(--ph-[\w-]+)\s*,\s*((?:[^()]|\([^()]*\))*?)\s*\)/g, '$2')

const normalize = (text) => {
  let out = unwrapTemplate(text)
  out = stripJsonScripts(out)
  out = revertImages(out)
  out = revertIds(out)
  out = revertFaq(out)
  out = revertDecoding(out)
  out = revertVars(out)
  return out.replace(/\r\n/g, '\n').trim()
}

const context = (text, index) => {
  const from = Math.max(0, index - 80)
  return text.slice(from, index + 120).replace(/\n/g, '\\n')
}

const firstDiff = (a, b) => {
  const n = Math.min(a.length, b.length)
  for (let i = 0; i < n; i++) if (a[i] !== b[i]) return i
  return a.length === b.length ? -1 : n
}

const lines = readLines()
const map = buildImageMap()
const html = await load(source)
let failures = 0
let checked = 0

for (const section of SECTIONS) {
  if (only && section.slug !== only) continue
  const roots = findRoots(html, section.slug)
  if (!roots.length) {
    console.log('MISS  ' + section.slug.padEnd(14) + 'no widget root on the page')
    failures++
    continue
  }
  const { html: expected } = rewriteAssets(sliceSection(lines, section), map)
  roots.forEach((root, index) => {
    const actual = normalize(root)
    checked++
    if (actual === expected.trim()) {
      console.log('OK    ' + section.slug.padEnd(14) + (roots.length > 1 ? 'instance ' + (index + 1) : ''))
      return
    }
    failures++
    const at = firstDiff(actual, expected.trim())
    console.log('FAIL  ' + section.slug.padEnd(14) + (roots.length > 1 ? 'instance ' + (index + 1) + ' ' : '') + 'first diff at byte ' + at)
    if (at === -1) {
      console.log('      lengths differ: rendered ' + actual.length + ' vs template ' + expected.trim().length)
    } else {
      console.log('      rendered: ' + context(actual, at))
      console.log('      template: ' + context(expected.trim(), at))
    }
    if (verbose) {
      fs.writeFileSync('dist/plans/parity-' + section.slug + '-' + index + '-actual.html', actual, 'utf8')
      fs.writeFileSync('dist/plans/parity-' + section.slug + '-expected.html', expected.trim(), 'utf8')
    }
  })
}

console.log('')
console.log('checked ' + checked + ' instance(s), ' + failures + ' failure(s)' + (Object.keys(DYNAMIC_CLASSES).length ? ' (pipeline JSON script and <template> wrapper excluded by design)' : ''))
process.exit(failures ? 1 : 0)
