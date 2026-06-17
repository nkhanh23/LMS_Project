import fs from 'node:fs';
import path from 'node:path';

globalThis.DOMMatrix = class DOMMatrix {
  constructor() {
    this.a = 1; this.b = 0; this.c = 0; this.d = 1; this.e = 0; this.f = 0;
  }
  translate() { return this; }
  scale() { return this; }
  multiply() { return this; }
  inverse() { return this; }
};
globalThis.ImageData = class ImageData {};
globalThis.Path2D = class Path2D {};
const pdfjs = await import('file:///C:/Users/nkhan/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/pdfjs-dist/legacy/build/pdf.mjs');
const file = new Uint8Array(fs.readFileSync('khanh-turning.pdf'));
const doc = await pdfjs.getDocument({ data: file, disableWorker: true }).promise;
console.log('pages=' + doc.numPages);

for (let pageNo = 1; pageNo <= doc.numPages; pageNo++) {
  const page = await doc.getPage(pageNo);
  const text = await page.getTextContent();
  console.log(`\n--- PAGE ${pageNo} textItems=${text.items.length} ---`);
  console.log(text.items.map((item) => item.str).join(' ').slice(0, 5000));

  const ops = await page.getOperatorList();
  const names = Object.entries(pdfjs.OPS)
    .filter(([, value]) => ops.fnArray.includes(value))
    .map(([key]) => key);
  console.log('ops=' + names.join(','));
}
