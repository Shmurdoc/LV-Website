const { chromium } = require('playwright');
const BASE = 'http://127.0.0.1:8012';
let P = 0, F = 0;
const R = [];
function ok(n, pass, ev) { if (pass) { P++; R.push(`  PASS  ${n}`); } else { F++; R.push(`  FAIL  ${n}  — ${ev}`); } }

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx = await browser.newContext();
  const page = await ctx.newPage();

  // ═══ 1. PUBLIC ROUTES 200 ═══
  console.log('\n── 1. Public routes ──');
  for (const r of ['/', '/accomodation/', '/gallery/', '/safari/', '/contact/',
    '/bachelor-apartment/', '/classic-apartment-2/', '/comfort-apartment-3/',
    '/deluxe-apartment-4/', '/api/health']) {
    const s = (await page.goto(BASE + r)).status();
    ok(`GET ${r} → ${s}`, s === 200, `status=${s}`);
  }

  // ═══ 2. HEALTH JSON ═══
  console.log('\n── 2. Health ──');
  await page.goto(BASE + '/api/health');
  const h = JSON.parse(await page.textContent('body'));
  ok('health ok=true', h.ok === true, JSON.stringify(h));
  ok('health db=true', h.checks.db === true, JSON.stringify(h.checks));

  // ═══ 3. HOMEPAGE HERO/NAV/FOOTER ═══
  console.log('\n── 3. Homepage hero / nav / footer ──');
  await page.goto(BASE + '/');
  const html = await page.innerHTML('body');
  ok('hero (hero--slideshow)', html.includes('hero--slideshow'), 'class not found');
  ok('nav exists', html.includes('nav__links') || html.includes('<nav'), 'no nav');
  ok('footer exists', html.includes('<footer'), 'no <footer>');
  const navLinks = await page.$$eval('nav a', els => els.map(e => e.textContent.trim()).filter(Boolean));
  for (const l of ['Home','Accommodation','Safari','Gallery','Contact'])
    ok(`nav "${l}"`, navLinks.includes(l), navLinks.join(', '));
  ok('nav "Book Now"', navLinks.some(t => t.includes('Book')), navLinks.join(', '));

  // ═══ 4. HERO HEADLINE ═══
  console.log('\n── 4. Hero headline ──');
  ok('headline "Prepare to embark"', html.includes('Prepare to embark'), 'not found');

  // ═══ 5. PRICING ═══
  console.log('\n── 5. Pricing ──');
  const prices = (html.match(/R[\d\s,]+/g) || []).map(p => p.replace(/[\s,]/g, ''));
  ok('R950', prices.includes('R950'), prices.join(' '));
  ok('R1050', prices.includes('R1050'), prices.join(' '));
  ok('R1200', prices.includes('R1200'), prices.join(' '));

  // ═══ 6. ACCOMMODATION PAGE ═══
  console.log('\n── 6. Accommodation ──');
  await page.goto(BASE + '/accomodation/');
  const accH = await page.innerHTML('body');
  for (const s of ['bachelor-apartment','classic-apartment-2','comfort-apartment-3','deluxe-apartment-4'])
    ok(`link ${s}`, accH.includes(s), 'not found');

  // ═══ 7. APARTMENT DETAIL PAGES ═══
  console.log('\n── 7. Apartment pages ──');
  for (const [slug, name] of [
    ['bachelor-apartment','Classic Apartment 1'],
    ['classic-apartment-2','Classic Apartment 2'],
    ['comfort-apartment-3','Comfort Apartment 3'],
    ['deluxe-apartment-4','Deluxe Apartment 4']]) {
    await page.goto(BASE + '/' + slug + '/');
    ok(`${slug} title`, (await page.title()).includes(name.split(' ')[0]), await page.title());
  }

  // ═══ 8. GALLERY ═══
  console.log('\n── 8. Gallery ──');
  await page.goto(BASE + '/gallery/');
  const gH = await page.innerHTML('body');
  for (const c of ['Luxe Bedrooms','Kitchens','Luxe Bathrooms','Luxe Living Rooms','Luxe Outdoors'])
    ok(`cat "${c}"`, gH.includes(c), 'not found');
  ok('gallery images ≥15', (gH.match(/Luxury Images/g) || []).length >= 15, 'count low');

  // ═══ 9. CONTACT FORM ═══
  console.log('\n── 9. Contact form ──');
  await page.goto(BASE + '/contact/');
  const cH = await page.innerHTML('body');
  ok('form exists', cH.includes('contactForm') || cH.includes('Send'), 'no form');
  ok('name field', cH.includes('name="name"') || cH.includes('id="name"'), 'no name');
  ok('email field', cH.includes('email') || cH.includes('Email'), 'no email');
  ok('message field', cH.includes('message') || cH.includes('Message'), 'no message');

  // ═══ 10. SAFARI PAGE ═══
  console.log('\n── 10. Safari ──');
  await page.goto(BASE + '/safari/');
  await page.waitForTimeout(2000);
  const sH = await page.innerHTML('body');
  const yt = [...new Set((sH.match(/youtu(?:\.be\/|be\.com\/embed\/)([A-Za-z0-9_-]+)/g) || []).map(u => u.split('/').pop()))];
  ok(`safari YouTube IDs (${yt.length})`, yt.length >= 2, yt.join(', '));

  // ═══ 11. FOOTER BOOKING LINK ═══
  console.log('\n── 11. Footer booking ──');
  await page.goto(BASE + '/');
  const bodyH = await page.innerHTML('body');
  ok('NightsBridge in page', bodyH.includes('nightsbridge') || bodyH.includes('38331'), 'booking URL missing');

  // ═══ 12. CSS TOKENS ═══
  console.log('\n── 12. Design tokens ──');
  await page.goto(BASE + '/');
  const tokens = await page.evaluate(() => {
    const cs = getComputedStyle(document.documentElement);
    return { navy: cs.getPropertyValue('--navy').trim(), gold: cs.getPropertyValue('--gold').trim(), sage: cs.getPropertyValue('--sage').trim() };
  });
  ok('--navy=#0B1A2E', tokens.navy === '#0B1A2E', `got "${tokens.navy}"`);
  ok('--gold=#8C7434', tokens.gold === '#8C7434', `got "${tokens.gold}"`);
  ok('--sage=#7A8C62', tokens.sage === '#7A8C62', `got "${tokens.sage}"`);

  // ═══ 13. SESSION COOKIE ═══
  console.log('\n── 13. Cookie flags ──');
  const cookies = await ctx.cookies();
  const sess = cookies.find(c => c.name === 'PHPSESSID');
  ok('PHPSESSID exists', !!sess, 'no cookie');
  if (sess) {
    ok('httponly', sess.httpOnly === true, JSON.stringify(sess));
    ok('samesite=Strict', sess.sameSite === 'Strict', JSON.stringify(sess));
  }

  // ═══ 14. ADMIN LOGIN ═══
  console.log('\n── 14. Admin login ──');
  await page.goto(BASE + '/admin/login');
  ok('login page', (await page.title()).includes('Sign In'), await page.title());
  const hasCSRF = await page.$eval('input[name="csrf_token"]', el => !!el.value).catch(() => false);
  ok('csrf token', hasCSRF, 'not found');
  ok('username input', await page.isVisible('input[name="username"]'), 'not visible');
  ok('password input', await page.isVisible('input[name="password"]'), 'not visible');
  await page.fill('input[name="username"]', 'admin');
  await page.fill('input[name="password"]', 'ViataLuxe2025!');
  await page.click('button[type="submit"], input[type="submit"]');
  await page.waitForURL('**/admin/dashboard', { timeout: 10000 });
  ok('redirects to dashboard', page.url().includes('/admin/dashboard'), page.url());
  ok('dashboard title', (await page.title()).includes('Admin'), await page.title());

  // ═══ 15. ADMIN SIDEBAR ═══
  console.log('\n── 15. Admin sidebar ──');
  const sideLinks = await page.$$eval('.sidebar-nav a .sidebar-label', els =>
    els.map(e => e.textContent.trim()).filter(Boolean)
  );
  for (const l of ['Dashboard','Pages','Sections','Apartments','Gallery','Safari','Testimonials','FAQs','Navigation','Settings'])
    ok(`sidebar "${l}"`, sideLinks.includes(l), sideLinks.join(', '));
  // Contact link has unread count badge — check href instead
  const sideHrefs = await page.$$eval('.sidebar-nav a', els => els.map(e => e.getAttribute('href')));
  ok('sidebar Contact href', sideHrefs.some(h => h && h.includes('/contact')), sideHrefs.join(', '));

  // ═══ 16. ALL ADMIN PAGES LOAD ═══
  console.log('\n── 16. Admin pages ──');
  for (const [path, kw] of [
    ['/admin/dashboard','Dashboard'],['/admin/pages','Home'],['/admin/sections','hero'],
    ['/admin/apartments','Bachelor'],['/admin/gallery','Luxe'],['/admin/safari','Kedibone'],
    ['/admin/testimonials','Kurhula'],['/admin/faqs','question'],['/admin/navigation','navigation'],
    ['/admin/contact','contact'],['/admin/settings','Settings']]) {
    await page.goto(BASE + path);
    await page.waitForTimeout(1500);
    const t = await page.textContent('body');
    ok(`${path} has "${kw}"`, t.includes(kw), `not in body (${t.length} chars)`);
  }

  // ═══ 17. FRONTEND SECTION TEMPLATES — zero inline styles ═══
  console.log('\n── 17. Frontend section templates ──');
  const fs = require('fs');
  const path = require('path');
  const sectionsDir = 'C:\\wamp64\\www\\work\\final website\\templates\\sections';
  let sectionFiles = 0, sectionInlineStyles = 0;
  const sectionIssues = [];
  for (const f of fs.readdirSync(sectionsDir).filter(f => f.endsWith('.php'))) {
    sectionFiles++;
    const src = fs.readFileSync(path.join(sectionsDir, f), 'utf8');
    const matches = src.match(/style\s*=\s*["']/g) || [];
    if (matches.length > 0) { sectionInlineStyles += matches.length; sectionIssues.push(`${f}(${matches.length})`); }
  }
  ok(`section partials: ${sectionFiles} files, ${sectionInlineStyles} inline styles`, sectionInlineStyles === 0, sectionIssues.join(', '));

  // ═══ 18. DB HEALTH ═══
  console.log('\n── 18. DB ──');
  ok('DB connected', h.checks.db === true, 'db check');

  await browser.close();
  console.log('\n' + R.join('\n'));
  console.log(`\n${'═'.repeat(50)}\n  TOTAL: ${P+F}  PASS: ${P}  FAIL: ${F}\n${'═'.repeat(50)}`);
  process.exit(F > 0 ? 1 : 0);
})();
