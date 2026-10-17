/* Harvest the design's repeater-row icons from the golden markup into
   widgets/ph-ce-lib/icons.php (bodies keyed by stable content hash) plus
   dist/plans/ce-icons-map.json (per widget, slot order -> key).
   Idempotent: re-run refreshes both outputs. Run: node bin/ce-icons.mjs */

import fs from 'fs'
import path from 'path'
import crypto from 'crypto'
import { ROOT } from './ce-lib.mjs'

/* Per widget: slot name + regex with one capture group around the <svg> body. */
const SLOTS = [
  { slug: 'ce-solution', slot: 'card', re: /<span class="ss-num">\d+<\/span><span class="icon"[^>]*>(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-services', slot: 'card', re: /<div class="icon-[ob]">(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-pipeline', slot: 'stage', re: /<span class="pl-ic">(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-process', slot: 'step', re: /<div class="stk-out"><span class="icon"[^>]*>(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-process', slot: 'feature', re: /<span class="fw-ic">(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-why', slot: 'card', re: /<span class="icon-top">(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-signal', slot: 'account', re: /<div class="ce-acct-h"><span class="icon-o"[^>]*>(<svg[\s\S]*?<\/svg>)/g },
  { slug: 'ce-signal', slot: 'stat', re: /<div class="glass ce-stat"><span class="icon(?:-b)?"[^>]*>(<svg[\s\S]*?<\/svg>)/g },
  {
    slug: 'ce-footer',
    slot: 'social',
    re: /<a href="#" aria-label="([^"]+)" class="ft-soc">(<svg[\s\S]*?<\/svg>)/g,
    labelGroup: 1
  },
  {
    slug: 'ce-footer',
    slot: 'ai',
    re: /<a class="ai-btn" href="[^"]*" target="_blank" rel="noopener" aria-label="Summarise Prospects Hive with ([^"]+)"[^>]*>(<svg[\s\S]*?<\/svg>)/g,
    labelGroup: 1
  }
]

const hash = (body) => crypto.createHash('md5').update(body).digest('hex').slice(0, 10)
const phpString = (s) => "'" + s.replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'"

const icons = new Map()
const map = {}

for (const { slug, slot, re, labelGroup } of SLOTS) {
  const file = path.join(ROOT, 'widgets', 'ph-' + slug, 'markup.html')
  const html = fs.readFileSync(file, 'utf8')
  const entries = []
  let m
  while ((m = re.exec(html))) {
    const body = labelGroup ? m[2] : m[1]
    if (/\n|\r/.test(body)) throw new Error(slug + ' ' + slot + ': svg body spans lines, regex too greedy')
    const key = hash(body)
    if (!icons.has(key)) icons.set(key, body)
    else if (icons.get(key) !== body) throw new Error('hash collision on ' + key)
    entries.push({ key, label: labelGroup ? m[labelGroup] : null })
  }
  if (!entries.length) throw new Error(slug + ' ' + slot + ': no icons matched')
  if (!map[slug]) map[slug] = {}
  map[slug][slot] = entries
}

const out = []
out.push('<?php')
out.push('/**')
out.push(' * Cold Email design icon set.')
out.push(' *')
out.push(' * Bodies harvested verbatim from the Cold Email design export; keys are stable')
out.push(' * content hashes. Select labels live in each widget’s controls-content.php.')
out.push(' */')
out.push('')
out.push("if ( ! defined( 'ABSPATH' ) ) {")
out.push('\texit;')
out.push('}')
out.push('')
out.push("if ( ! function_exists( 'nexora_ph_ce_icons' ) ) {")
out.push('\t/**')
out.push('\t * All design icons, keyed by content hash.')
out.push('\t *')
out.push('\t * @return array<string, string>')
out.push('\t */')
out.push('\tfunction nexora_ph_ce_icons(): array {')
out.push('\t\treturn [')
for (const [key, body] of icons) {
  out.push('\t\t\t' + phpString(key) + ' => ' + phpString(body) + ',')
}
out.push('\t\t];')
out.push('\t}')
out.push('}')
out.push('')
out.push("if ( ! function_exists( 'nexora_ph_ce_icon' ) ) {")
out.push('\t/**')
out.push('\t * One icon body by key; unknown keys render nothing.')
out.push('\t *')
out.push('\t * @param string $key Icon key.')
out.push('\t */')
out.push('\tfunction nexora_ph_ce_icon( $key ): string {')
out.push('\t\t$icons = nexora_ph_ce_icons();')
out.push("\t\t$key   = (string) $key;")
out.push('')
out.push("\t\treturn isset( $icons[ $key ] ) ? $icons[ $key ] : '';")
out.push('\t}')
out.push('}')
out.push('')

fs.mkdirSync(path.join(ROOT, 'widgets', 'ph-ce-lib'), { recursive: true })
fs.writeFileSync(path.join(ROOT, 'widgets', 'ph-ce-lib', 'icons.php'), out.join('\n'), 'utf8')
fs.mkdirSync(path.join(ROOT, 'dist', 'plans'), { recursive: true })
fs.writeFileSync(path.join(ROOT, 'dist', 'plans', 'ce-icons-map.json'), JSON.stringify(map, null, '\t'), 'utf8')

console.log('icons: ' + icons.size + ' distinct glyphs -> widgets/ph-ce-lib/icons.php')
for (const [slug, slots] of Object.entries(map)) {
  const counts = Object.entries(slots).map(([slot, entries]) => slot + '=' + entries.length).join(', ')
  console.log('  ' + slug + ': ' + counts)
}
