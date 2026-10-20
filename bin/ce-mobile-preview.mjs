import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..')
const widgets = [
	'hero',
	'problem',
	'solution',
	'services',
	'pipeline',
	'process',
	'outlook',
	'why',
	'signal',
]

const parts = []
parts.push('<!DOCTYPE html><html><head><meta charset="utf-8">')
parts.push('<meta name="viewport" content="width=device-width,initial-scale=1">')
parts.push('<title>CE mobile preview</title>')
parts.push('<link rel="stylesheet" href="../../assets/css/nexora-ph-shared.css">')
for (const w of widgets) {
	parts.push(
		`<link rel="stylesheet" href="../../assets/css/nexora-ph-ce-${w}.css">`
	)
}
parts.push(
	'<style>body{margin:0;background:#fff}img{max-width:100%;height:auto}</style>'
)
parts.push('</head><body>')
for (const w of widgets) {
	const markup = fs.readFileSync(
		path.join(root, `widgets/ph-ce-${w}/markup.html`),
		'utf8'
	)
	parts.push(`<div class="nexora-ph nexora-ph-ce-${w}">${markup}</div>`)
}
parts.push('</body></html>')

const out = path.join(root, 'dist/plans/ce-mobile-preview.html')
fs.mkdirSync(path.dirname(out), { recursive: true })
fs.writeFileSync(out, parts.join('\n'))
console.log('wrote', out)
