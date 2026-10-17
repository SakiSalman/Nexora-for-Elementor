/* Shared helpers for the Cold Email (CE) extraction tooling.
   Section map, image map, and the CSS parser used by ce-extract.mjs, ce-css-diff.mjs, ce-parity.mjs.
   Workspace rule: no semicolons, const arrow functions. */

import fs from 'fs'
import path from 'path'
import crypto from 'crypto'

export const ROOT = process.cwd()
export const TEMPLATE_PATH = path.join(ROOT, 'dist', 'Cold Email (1)', 'ColdEmail.dc.html')
export const TEMPLATE_ASSETS = path.join(ROOT, 'dist', 'Cold Email (1)', 'assets')
export const PROSPECTS_DIR = path.join(ROOT, 'assets', 'images', 'prospects')

/* Included sections, 1-based inclusive line ranges in ColdEmail.dc.html.
   NAV (744) and CE8 COMPARISON (1080) are built elsewhere and excluded. */
export const SECTIONS = [
  { slug: 'ce-hero', start: 794, end: 841 },
  { slug: 'ce-problem', start: 843, end: 872 },
  { slug: 'ce-solution', start: 874, end: 908 },
  { slug: 'ce-services', start: 910, end: 935 },
  { slug: 'ce-pipeline', start: 937, end: 962 },
  { slug: 'ce-process', start: 964, end: 1057 },
  { slug: 'ce-outlook', start: 1059, end: 1076 },
  { slug: 'ce-why', start: 1109, end: 1125 },
  { slug: 'ce-signal', start: 1153, end: 1185 }
]

/* Kept for the ce-extract import; empty since the CE Reviews / CE FAQ widgets
   were removed (their two images go with them). Leaving it empty keeps
   ce-extract safe to re-run after a design re-export. */
export const NEW_IMAGES = {}

/* Classes produced at runtime by DCLogic renderVals, invisible in markup.html (plan: dynamic stage count).
   A markup scan alone would drop these rules and break the pipeline progress bar. */
export const DYNAMIC_CLASSES = {
  'ce-solution': ['under'],
  'ce-pipeline': ['pl-track', 'pl-f0', 'pl-f1', 'pl-f2', 'pl-f3', 'pl-f4', 'pl-node', 'run', 'run2']
}

export const readTemplate = () => fs.readFileSync(TEMPLATE_PATH, 'utf8')

export const readLines = () => readTemplate().split(/\r?\n/)

export const sliceSection = (lines, section) =>
  lines.slice(section.start - 1, section.end).join('\n')

export const md5 = (file) =>
  crypto.createHash('md5').update(fs.readFileSync(file)).digest('hex')

/* Template file name (assets/<hash>.<ext>, the name markup references) -> friendly name.
   Resolved by content hash against assets/images/prospects/, plus the NEW_IMAGES overrides. */
export const buildImageMap = () => {
  const byContent = {}
  for (const file of fs.readdirSync(PROSPECTS_DIR)) {
    const full = path.join(PROSPECTS_DIR, file)
    if (!fs.statSync(full).isFile()) continue
    byContent[md5(full)] = file
  }
  const map = {}
  for (const file of fs.readdirSync(TEMPLATE_ASSETS)) {
    const full = path.join(TEMPLATE_ASSETS, file)
    if (!fs.statSync(full).isFile()) continue
    const name = NEW_IMAGES[file] || byContent[md5(full)]
    if (name) map[file.toLowerCase()] = name
  }
  return map
}

/* ./assets/<hash>.<ext> -> assets/<friendly-name> (the renderer maps assets/ to the plugin URL). */
export const rewriteAssets = (text, map) => {
  const missing = []
  const out = text.replace(/\.?\/?assets\/([0-9a-f]{32})\.([a-z0-9]+)/gi, (whole, hash, ext) => {
    const key = (hash + '.' + ext).toLowerCase()
    const name = map[key]
    if (!name) {
      if (!missing.includes(key)) missing.push(key)
      return whole
    }
    return 'assets/' + name
  })
  return { html: out, missing }
}

/* --- CSS parsing --------------------------------------------------------- */

const stripComments = (css) => css.replace(/\/\*[\s\S]*?\*\//g, '')

const findBlockEnd = (css, open) => {
  let depth = 0
  let i = open
  while (i < css.length) {
    const ch = css[i]
    if (ch === '"' || ch === "'") {
      const quote = ch
      i++
      while (i < css.length && css[i] !== quote) i++
    } else if (ch === '{') depth++
    else if (ch === '}') {
      depth--
      if (depth === 0) return i
    }
    i++
  }
  return css.length - 1
}

/* Parse CSS into an ordered node list:
   { type: 'rule', selector, body }
   { type: 'keyframes', name, body }
   { type: 'media' | 'supports', query, nodes }
   { type: 'other', raw }  (font-face, import, ...) */
export const parseCss = (css) => {
  const src = stripComments(css)
  const nodes = []
  let i = 0
  while (i < src.length) {
    const open = src.indexOf('{', i)
    if (open === -1) break
    const close = findBlockEnd(src, open)
    const header = src.slice(i, open).trim()
    const body = src.slice(open + 1, close)
    if (!header) {
      i = close + 1
      continue
    }
    if (/^@keyframes\b/i.test(header)) {
      nodes.push({ type: 'keyframes', name: header.replace(/^@(-\w+-)?keyframes\s+/i, '').trim(), body })
    } else if (/^@(media|supports)\b/i.test(header)) {
      nodes.push({ type: header.slice(1).split(/\s+/)[0].toLowerCase(), query: header, nodes: parseCss(body) })
    } else if (header.startsWith('@')) {
      nodes.push({ type: 'other', raw: header + '{' + body + '}' })
    } else {
      nodes.push({ type: 'rule', selector: header, body: body.trim() })
    }
    i = close + 1
  }
  return nodes
}

export const walkRules = (nodes, fn) => {
  for (const node of nodes) {
    if (node.type === 'rule') fn(node)
    else if (node.nodes) walkRules(node.nodes, fn)
  }
}

export const collectKeyframes = (nodes, into = {}) => {
  for (const node of nodes) {
    if (node.type === 'keyframes') into[node.name] = node
    else if (node.nodes) collectKeyframes(node.nodes, into)
  }
  return into
}

/* Selector helpers --------------------------------------------------------- */

export const splitSelector = (selector) =>
  selector.split(',').map((part) => part.replace(/\s+/g, ' ').trim()).filter(Boolean)

export const classesOf = (text) => {
  const out = []
  const re = /\.([A-Za-z0-9_-]+)/g
  let m
  while ((m = re.exec(text))) if (!out.includes(m[1])) out.push(m[1])
  return out
}

/* #process -> [data-ph-anchor="process"]: the renderer suffixes ids with the Elementor id. */
export const convertIds = (selector) => selector.replace(/#([A-Za-z][\w-]*)/g, '[data-ph-anchor="$1"]')

export const anchorsOf = (text) => {
  const out = []
  const re = /data-ph-anchor="([^"]+)"/g
  let m
  while ((m = re.exec(text))) if (!out.includes(m[1])) out.push(m[1])
  return out
}

/* Normalized comparison keys: the renderer and PH CSS may carry .nexora-ph prefixes. */
export const normalizeSelector = (selector) =>
  splitSelector(selector)
    .map((part) => part.replace(/\.nexora-ph\s+/g, '').replace(/\.nexora-ph(?=[.\s:#[\]$])/g, '').replace(/\s+/g, ' ').trim())
    .filter(Boolean)
    .join(',')

export const normalizeBody = (body) => body.replace(/\s+/g, ' ').trim()

export const normalizeRule = (selector, body) => normalizeSelector(selector) + '{' + normalizeBody(body) + '}'

/* Classes referenced by a section's markup, plus runtime-generated classes. */
export const markupClasses = (html) => {
  const out = []
  const re = /class="([^"]*)"/g
  let m
  while ((m = re.exec(html))) {
    for (const token of m[1].split(/\s+/)) {
      if (!token || token.includes('{')) continue
      if (!out.includes(token)) out.push(token)
    }
  }
  return out
}

export const markupIds = (html) => {
  const out = []
  const re = /\sid="([^"]+)"/g
  let m
  while ((m = re.exec(html))) if (!out.includes(m[1])) out.push(m[1])
  return out
}

export const widgetCssPath = (slug) => path.join(ROOT, 'assets', 'css', 'nexora-ph-' + slug + '.css')

export const markupPath = (slug) => path.join(ROOT, 'widgets', 'ph-' + slug, 'markup.html')
