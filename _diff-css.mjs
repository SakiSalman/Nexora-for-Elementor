import fs from 'fs'
import path from 'path'

const design = fs.readFileSync('c:/Users/USER/Downloads/Home page/Redesign.dc.html', 'utf8')
const root = process.cwd()
const style = design.match(/<style>([\s\S]*?)<\/style>/)[1]
const cssFiles = fs.readdirSync(path.join(root, 'assets/css')).filter((name) => name.startsWith('nexora-ph-'))
let current = ''
for (const name of cssFiles) current += fs.readFileSync(path.join(root, 'assets/css', name), 'utf8') + '\n'

function rules(css) {
  const out = []
  const stripped = css.replace(/\/\*[\s\S]*?\*\//g, '')
  const re = /([^{}]+)\{([^{}]*)\}/g
  let match
  while ((match = re.exec(stripped))) {
    const sel = match[1].replace(/\s+/g, ' ').trim()
    const body = match[2].replace(/\s+/g, ' ').trim()
    if (!sel || sel.startsWith('@')) continue
    out.push(sel + '{' + body + '}')
  }
  return out
}

const oldRules = new Set(rules(current).map((rule) => rule.replace(/\.nexora-ph\s+/g, '').replace(/\.nexora-ph/g, '')))
const fresh = []
for (const rule of rules(style)) {
  const bare = rule.replace(/\.nexora-ph\s+/g, '').replace(/\.nexora-ph/g, '')
  if (!oldRules.has(bare) && !oldRules.has(rule)) fresh.push(rule)
}
fs.writeFileSync('_new-css.txt', fresh.join('\n'))
console.log('new rules', fresh.length)
console.log(fresh.slice(0, 80).join('\n'))
