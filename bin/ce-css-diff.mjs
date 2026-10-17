/* 0.2: classify every template CSS rule used by the 13 CE sections and write the per-widget delta files.
   Buckets per rule:
     ph       identical selector+body already in PH CSS (skip)
     conflict same selector, different body (copy)
     new      selector not in PH CSS at all (copy, design source order)
     global   element-only selector with no class/anchor (reset-level, left to nexora-ph-shared)
     unused   only referenced by nav / CE2 / CE8 / dead design leftovers
   Every copied rule is prefixed with .nexora-ph-ce-{name} so no widget can leak into another.
   Run: node bin/ce-css-diff.mjs */

import fs from 'fs'
import path from 'path'
import {
  ROOT, SECTIONS, DYNAMIC_CLASSES, readTemplate, readLines, widgetCssPath, markupPath,
  parseCss, walkRules, collectKeyframes, splitSelector, classesOf, convertIds, anchorsOf,
  normalizeSelector, normalizeBody, normalizeRule, markupClasses, markupIds
} from './ce-lib.mjs'

const templateCss = () => {
  const html = readTemplate()
  const blocks = []
  const re = /<style>([\s\S]*?)<\/style>/g
  let m
  while ((m = re.exec(html))) blocks.push(m[1])
  return blocks.join('\n')
}

const phCss = () => {
  const dir = path.join(ROOT, 'assets', 'css')
  let all = ''
  for (const name of fs.readdirSync(dir)) {
    if (!name.startsWith('nexora-ph-') || !name.endsWith('.css')) continue
    if (name.startsWith('nexora-ph-ce-')) continue
    all += fs.readFileSync(path.join(dir, name), 'utf8') + '\n'
  }
  return all
}

const collectRules = (nodes) => {
  const list = []
  for (const node of nodes) {
    if (node.type === 'rule') list.push(node)
    else if (node.nodes) list.push(...collectRules(node.nodes))
  }
  return list
}

/* --- corpora ----------------------------------------------------------------- */

const templateText = readTemplate()
const templateNodes = parseCss(templateCss())
const phNodes = parseCss(phCss())

const phRules = new Set()
const phSelectors = new Set()
walkRules(phNodes, (rule) => {
  phRules.add(normalizeRule(rule.selector, rule.body))
  for (const part of splitSelector(normalizeSelector(rule.selector))) phSelectors.add(part)
})

const phKeyframes = collectKeyframes(phNodes)
const templateKeyframes = collectKeyframes(templateNodes)
const templateRuleList = collectRules(templateNodes)

/* --- per-widget usage -------------------------------------------------------- */

const widgets = SECTIONS.map((section) => {
  const html = fs.readFileSync(markupPath(section.slug), 'utf8')
  const used = new Set([...markupClasses(html), ...(DYNAMIC_CLASSES[section.slug] || [])])
  const anchors = new Set(markupIds(html))
  return { ...section, used, anchors, emit: [] }
})

/* Classes that appear in the template but in no included section (nav, CE2, CE8, dead leftovers).
   A rule mentioning one of these belongs to a skipped section even if it also mentions a base class. */
const allTemplateClasses = new Set()
{
  const whole = readLines().slice(10, 1298).join('\n')
  for (const cls of markupClasses(whole)) allTemplateClasses.add(cls)
}
const universe = new Set()
for (const widget of widgets) for (const cls of widget.used) universe.add(cls)
const foreign = new Set([...allTemplateClasses].filter((cls) => !universe.has(cls)))

/* Classes that exist only in the template's CSS, never in any markup (dead design code like
   .fw-sec / .ftab / .gs-step). Runtime state classes live in DYNAMIC_CLASSES and stay allowed. */
const cssOnly = new Set()
{
  const cssText = [templateCss(), phCss()].join('\n')
  const seen = new Set()
  const re = /\.([A-Za-z0-9_-]+)/g
  let m
  while ((m = re.exec(cssText))) seen.add(m[1])
  for (const cls of seen) if (!allTemplateClasses.has(cls) && !universe.has(cls)) cssOnly.add(cls)
}

const ruleParts = (rule) => splitSelector(convertIds(rule.selector))

const isUsed = (widget, part) => {
  const anchors = anchorsOf(part)
  for (const anchor of anchors) if (!widget.anchors.has(anchor)) return false
  const classes = classesOf(part)
  let hit = anchors.length > 0
  for (const cls of classes) {
    if (foreign.has(cls)) return false
    if (cssOnly.has(cls) && !widget.used.has(cls)) return false
    if (widget.used.has(cls)) hit = true
  }
  return hit
}

const bucketOf = (rule) => {
  const converted = convertIds(rule.selector)
  if (phRules.has(normalizeRule(converted, rule.body))) return 'ph'
  const known = splitSelector(normalizeSelector(converted)).some((part) => phSelectors.has(part))
  return known ? 'conflict' : 'new'
}

/* --- keyframe renames -------------------------------------------------------- */

const renames = {}

const freeKeyName = (name) => {
  let candidate = 'ce-' + name
  let n = 2
  while (templateKeyframes[candidate] || phKeyframes[candidate]) candidate = 'ce-' + name + '-' + n++
  return candidate
}

const renameRefs = (body, oldName, newName) =>
  body.replace(new RegExp('(^|[;\\s,(])' + oldName + '(?=\\s|[.(,]|$)', 'g'), '$1' + newName)

const referencedKeyframes = (body) => {
  const names = []
  for (const m of body.matchAll(/animation(?:-name)?\s*:\s*([^;]+)/gi)) {
    for (const token of m[1].split(',')) {
      const name = token.trim().split(/\s+/)[0]
      if (name && name !== 'none' && templateKeyframes[name] && !names.includes(name)) names.push(name)
    }
  }
  return names
}

/* A template keyframe is "taken" when a copied rule references it and its body differs from PH's.
   Collected below, in classification order, only from rules a widget actually takes. */

/* --- classification + emission ---------------------------------------------- */

const globalReport = []

const planFor = templateRuleList.map((rule) => {
  let bucket = bucketOf(rule)
  const bare = ruleParts(rule).every((part) => !classesOf(part).length && !anchorsOf(part).length)
  if (bare) bucket = 'global'
  const users = bucket === 'ph' || bucket === 'global' ? [] : widgets.filter((widget) => ruleParts(rule).some((part) => isUsed(widget, part))).map((w) => w.slug)
  if (bucket !== 'ph' && bucket !== 'global' && !users.length) bucket = 'unused'
  return { rule, bucket, users }
})

/* rename a keyframe only when a taken rule references it and its body differs from PH's */
for (const { rule, users } of planFor) {
  if (!users.length) continue
  for (const name of referencedKeyframes(rule.body)) {
    const phKf = phKeyframes[name]
    if (phKf && normalizeBody(phKf.body) === normalizeBody(rule.body)) continue
    if (!renames[name]) renames[name] = freeKeyName(name)
  }
}

for (const rule of templateRuleList) {
  rule._body = rule.body
  for (const [oldName, newName] of Object.entries(renames)) rule._body = renameRefs(rule._body, oldName, newName)
}

for (const { rule, bucket, users } of planFor) {
  globalReport.push({ selector: rule.selector, bucket, users })
  if (!users.length) continue
  for (const widget of widgets) {
    const mine = ruleParts(rule).filter((part) => isUsed(widget, part))
    if (!mine.length) continue
    widget.emit.push({
      node: rule,
      bucket,
      text: mine.map((part) => '.nexora-ph-' + widget.slug + ' ' + part).join(',\n') + '{' + rule._body + '}'
    })
  }
}

const rebuild = (emit) => {
  const out = []
  let i = 0
  while (i < emit.length) {
    const entry = emit[i]
    if (!entry.node._wrapper) {
      out.push(entry.text)
      i++
      continue
    }
    const inner = []
    let j = i
    while (j < emit.length && emit[j].node._wrapper === entry.node._wrapper) {
      inner.push(emit[j].text)
      j++
    }
    out.push(entry.node._wrapper.query + '{' + inner.join('') + '}')
    i = j
  }
  return out.join('\n')
}

/* tag rules that live inside @media / @supports so the wrapper survives */
const markWrappers = (nodes, wrapper) => {
  for (const node of nodes) {
    if (node.type === 'rule') node._wrapper = wrapper
    else if (node.nodes) markWrappers(node.nodes, node.type === 'rule' ? wrapper : node)
  }
}
markWrappers(templateNodes, null)

const kfRaw = (name) => {
  const m = templateText.match(new RegExp('@keyframes\\s+' + name.replace(/-/g, '\\-') + '\\s*\\{'))
  if (!m) return ''
  let depth = 0
  const open = templateText.indexOf('{', m.index)
  let end = templateText.length - 1
  for (let j = open; j < templateText.length; j++) {
    if (templateText[j] === '{') depth++
    else if (templateText[j] === '}') {
      depth--
      if (depth === 0) {
        end = j
        break
      }
    }
  }
  return templateText.slice(m.index, end + 1)
}

const widgetKeyframes = (widget) => {
  const needed = new Set()
  for (const entry of widget.emit) for (const name of referencedKeyframes(entry.text)) needed.add(name)
  const blocks = []
  for (const [name, kf] of Object.entries(templateKeyframes)) {
    if (!needed.has(name)) continue
    const finalName = renames[name] || name
    if (renames[name]) blocks.push('@keyframes ' + finalName + '{' + kf.body + '}')
    else {
      const raw = kfRaw(name)
      if (raw) blocks.push(raw)
    }
  }
  return blocks
}

/* --- write ------------------------------------------------------------------ */

const report = []
report.push('# Cold Email CSS classification (generated by bin/ce-css-diff.mjs)')
report.push('')
report.push('| # | Selector (first 90 chars) | Bucket | Taken by |')
report.push('| --- | --- | --- | --- |')
globalReport.forEach((row, index) => {
  const sel = row.selector.replace(/\s+/g, ' ').trim().slice(0, 90).replace(/\|/g, '\\|')
  const label = { ph: '— (identical in PH CSS)', global: '— (reset-level, PH shared)', unused: '— (unused by included sections)' }
  const users = row.users.length ? row.users.join(', ') : (label[row.bucket] || '—')
  report.push('| ' + (index + 1) + ' | `' + sel + '` | ' + row.bucket + ' | ' + users + ' |')
})

const counts = { ph: 0, conflict: 0, new: 0, global: 0, unused: 0 }
for (const row of globalReport) counts[row.bucket]++
report.push('')
report.push('Totals: ' + counts.ph + ' skip (identical in PH CSS), ' + counts.conflict + ' conflict (scoped copy), ' + counts.new + ' new (scoped copy), ' + counts.global + ' global (left to nexora-ph-shared), ' + counts.unused + ' unused (nav / CE2 / CE8 / dead design code).')
report.push('Keyframe renames: ' + (Object.keys(renames).length ? Object.entries(renames).map(([a, b]) => a + ' → ' + b).join(', ') : 'none'))
report.push('')
report.push('## Per-widget delta files')
report.push('')

for (const widget of widgets) {
  const header =
    '/* Cold Email ' + widget.slug + ' delta CSS. Generated by bin/ce-css-diff.mjs from dist/Cold Email (1)/ColdEmail.dc.html. */\n' +
    '/* Rules this section needs that nexora-ph-shared / PH widget CSS do not provide, in design source order.\n' +
    '   Every selector is prefixed with .nexora-ph-' + widget.slug + ' (the widget root) so nothing can leak to other widgets. */'
  const blocks = widgetKeyframes(widget)
  const css = header + '\n' + rebuild(widget.emit) + (blocks.length ? '\n' + blocks.join('\n') + '\n' : '')
  fs.writeFileSync(widgetCssPath(widget.slug), css, 'utf8')
  report.push('- `assets/css/nexora-ph-' + widget.slug + '.css` — ' + widget.emit.length + ' rules, ' + blocks.length + ' keyframe blocks, ' + Buffer.byteLength(css) + ' bytes')
}

fs.writeFileSync(path.join(ROOT, 'dist', 'plans', 'ce-css-classification.md'), report.join('\n') + '\n', 'utf8')

console.log('template rules: ' + globalReport.length + '  |  ph(skip): ' + counts.ph + '  conflict: ' + counts.conflict + '  new: ' + counts.new + '  global: ' + counts.global + '  unused: ' + counts.unused)
console.log('keyframe renames: ' + (Object.keys(renames).length ? Object.entries(renames).map(([a, b]) => a + ' -> ' + b).join(', ') : 'none'))
for (const widget of widgets) console.log(widget.slug.padEnd(14) + widget.emit.length + ' rules written')
console.log('report: dist/plans/ce-css-classification.md')
