import http from 'http'
import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..')
const port = Number(process.env.PORT || 8099)
const types = {
	'.html': 'text/html; charset=utf-8',
	'.js': 'text/javascript; charset=utf-8',
	'.mjs': 'text/javascript; charset=utf-8',
	'.css': 'text/css; charset=utf-8',
	'.png': 'image/png',
	'.jpg': 'image/jpeg',
	'.jpeg': 'image/jpeg',
	'.svg': 'image/svg+xml',
	'.woff2': 'font/woff2',
	'.webp': 'image/webp',
}

const server = http.createServer((req, res) => {
	let urlPath = decodeURIComponent((req.url || '/').split('?')[0])
	if (urlPath === '/') urlPath = '/dist/Cold Email (1)/ColdEmail.dc.html'
	const file = path.normalize(path.join(root, urlPath.replace(/^\//, '')))
	if (!file.startsWith(root) || !fs.existsSync(file) || fs.statSync(file).isDirectory()) {
		res.writeHead(404)
		res.end('404 ' + urlPath)
		return
	}
	const ext = path.extname(file).toLowerCase()
	res.writeHead(200, { 'Content-Type': types[ext] || 'application/octet-stream' })
	fs.createReadStream(file).pipe(res)
})

server.listen(port, () => {
	console.log(`serving ${root} on http://127.0.0.1:${port}`)
})
