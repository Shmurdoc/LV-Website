const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const base = 'file:///C:/work/V%20Area/final%20website/html/';
  const pages = ['index.html','rooms.html','gallery.html','safari.html','contact.html'];
  for (const p of pages) {
    const page = await ctx.newPage();
    await page.goto(base + p, { waitUntil: 'networkidle' });
    const name = p.replace('.html','');
    await page.screenshot({ path: 'C:/work/V Area/final website/screenshots/' + name + '-desktop.png', fullPage: true });
    console.log('Captured ' + name);
    await page.close();
  }
  await ctx.close();
  await browser.close();
  console.log('Done');
})();
