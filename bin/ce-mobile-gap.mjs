/**
 * Compare design mobile media rules vs CE widget delta CSS.
 * Usage: node bin/ce-mobile-gap.mjs
 */
import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..')
const html = fs.readFileSync(
	path.join(root, 'dist/Cold Email (1)/ColdEmail.dc.html'),
	'utf8'
)
const styles = [...html.matchAll(/<style[^>]*>([\s\S]*?)<\/style>/gi)]
	.map((m) => m[1])
	.join('\n')

const widgetMap = {
	'ce-hero': [
		'hero-sec',
		'ce-hv',
		'ce-seq',
		'ce-float',
		'ce-kpis',
		'ce-meter',
		'ce-steps',
		'logo-pill',
		'logo-chip',
		'stack-sm',
		'hc-chips',
		'hc-spark',
		'hero-rating',
		'hr-badge',
		'hr-top',
		'hr-stars',
		'ghost-word',
	],
	'ce-problem': [
		'pt-sec',
		'pt-wrap',
		'pt-card',
		'pt-tl',
		'pt-dot',
		'pt-num',
		'pt-chart',
		'ce-frow',
		'ce-leak',
		'ce-funnel',
		'ce-fbar',
	],
	'ce-solution': ['ss-wrap', 'ss-side', 'ss-card', 'ss-num', 'ss-sec'],
	'ce-services': ['g4', 'sv-wrap', 'stack-sm', 'hv-grid', 'hv-card'],
	'ce-pipeline': [
		'pl-track',
		'pl-card',
		'pl-points',
		'pl-node',
		'bn-grid',
		'bn-big',
		'opt-label',
	],
	'ce-process': [
		'stk-sec',
		'stk-card',
		'stk-meta',
		'stk-panel',
		'fw-vis',
		'fw-vis-board',
		'fw-chip',
		'fw-panel',
		'fw-bubble',
		'fw-node',
		'fw-nic',
		'ce-v1',
		'ce-v2',
		'ce-v5',
		'ce-g-grid',
		'g3',
	],
	'ce-outlook': [
		'ce-g-grid',
		'ce-g-barwrap',
		'ce-growth',
		'ce-frow',
		'ce-leak',
		'ce-funnel',
		'split',
	],
	'ce-why': ['g3', 'hv-grid', 'hv-card', 'icon-top'],
	'ce-signal': [
		'ce-sig-grid',
		'ce-stats',
		'ce-proc-img',
		'ce-90',
		'ce-side',
		'ce-road',
		'ce-tl',
		'ce-acct',
		'ce-mail',
		'ce-stat',
	],
}

const mediaRe =
	/@media\s*(\([^)]+\))\s*\{((?:[^{}]|\{[^{}]*\})*)\}/g
const blocks = []
let m
while ((m = mediaRe.exec(styles))) {
	const cond = m[1]
	if (!/max-width:\s*(760|1024)px|min-width:\s*1025px/.test(cond)) continue
	const body = m[2]
	const rules = [...body.matchAll(/([^{}@]+)\{([^}]*)\}/g)]
	for (const r of rules) {
		blocks.push({ cond, sel: r[1].trim(), decls: r[2].trim() })
	}
}

const relevant = (keys, sel) =>
	keys.some(
		(k) =>
			sel.includes(`.${k}`) ||
			sel.includes(`#${k}`) ||
			sel.split(/[\s,>+~]/).includes(k)
	)

for (const widget of Object.keys(widgetMap)) {
	const file = path.join(root, 'assets/css', `nexora-ph-${widget}.css`)
	const css = fs.readFileSync(file, 'utf8')
	const missing = []
	for (const b of blocks) {
		if (!relevant(widgetMap[widget], b.sel)) continue
		const classTokens = [...b.sel.matchAll(/\.([a-zA-Z0-9_-]+)/g)].map(
			(x) => x[1]
		)
		if (!classTokens.length && !b.sel.includes('hero-sec')) continue
		const parts = b.decls
			.split(';')
			.map((s) => s.trim())
			.filter(Boolean)
		for (const d of parts) {
			const prop = d.split(':')[0].trim()
			const val = d.split(':').slice(1).join(':').trim()
			if (!prop || !val) continue
			const valBare = val.replace(/\s*!important\s*$/, '').trim()
			const hasVal =
				css.includes(val) ||
				css.includes(valBare) ||
				css.includes(val.replace(/\s+/g, ''))
			if (hasVal) continue
			// property+selector proximity: skip if widget never uses the class
			const usesClass = classTokens.every((t) => css.includes(`.${t}`)) ||
				(b.sel.includes('hero-sec') && css.includes('hero-sec'))
			if (!usesClass && classTokens.length) {
				// class not in widget — skip (removed / unused)
				continue
			}
			missing.push({ cond: b.cond, sel: b.sel, decl: d })
		}
	}
	const seen = new Set()
	const uniq = missing.filter((x) => {
		const k = `${x.cond}|${x.sel}|${x.decl}`
		if (seen.has(k)) return false
		seen.add(k)
		return true
	})
	console.log(`\n==== ${widget} (${uniq.length} missing decls) ====`)
	for (const x of uniq) {
		console.log(`${x.cond} | ${x.sel} { ${x.decl} }`)
	}
}
