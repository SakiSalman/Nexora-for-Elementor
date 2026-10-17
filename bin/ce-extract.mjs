/* 0.1 + 0.4: slice the Cold Email sections into widgets/ph-ce-{name}/markup.html (golden copies)
   and copy the two new images into assets/images/prospects/.
   Run: node bin/ce-extract.mjs */

import fs from 'fs'
import path from 'path'
import { ROOT, SECTIONS, NEW_IMAGES, PROSPECTS_DIR, TEMPLATE_ASSETS, readLines, sliceSection, buildImageMap, rewriteAssets, markupPath } from './ce-lib.mjs'

const map = buildImageMap()
const lines = readLines()
const report = []
let failed = false

for (const [hash, name] of Object.entries(NEW_IMAGES)) {
  const src = path.join(TEMPLATE_ASSETS, hash)
  const dest = path.join(PROSPECTS_DIR, name)
  if (!fs.existsSync(src)) {
    console.error('missing template asset ' + hash)
    failed = true
    continue
  }
  fs.copyFileSync(src, dest)
  report.push('image  ' + hash + ' -> assets/images/prospects/' + name)
}

for (const section of SECTIONS) {
  const slice = sliceSection(lines, section)
  if (!slice.trim()) {
    console.error('empty slice for ' + section.slug)
    failed = true
    continue
  }
  const { html, missing } = rewriteAssets(slice, map)
  if (missing.length) {
    console.error(section.slug + ': unmapped images ' + missing.join(', '))
    failed = true
  }
  const dir = path.dirname(markupPath(section.slug))
  fs.mkdirSync(dir, { recursive: true })
  fs.writeFileSync(markupPath(section.slug), html, 'utf8')
  const srcRefs = (html.match(/assets\//g) || []).length
  report.push(
    section.slug.padEnd(14) +
    'lines ' + String(section.start).padStart(4) + '-' + String(section.end).padEnd(4) +
    ' ' + String(Buffer.byteLength(html)).padStart(6) + ' bytes  ' + srcRefs + ' image refs'
  )
}

for (const line of report) console.log(line)
if (failed) {
  console.error('ce-extract: FAILED')
  process.exit(1)
}
console.log('ce-extract: ' + SECTIONS.length + ' golden markup files written')
