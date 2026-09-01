const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE = 'http://127.0.0.1:8012';
const issues = [];
const screenshots = [];

function log(msg) { console.log(msg); }
function issue(severity, page, msg) {
  const entry = { severity, page, msg };
  issues.push(entry);
  const tag = severity === 'CRITICAL' ? '🔴' : severity === 'HIGH' ? '🟠' : severity === 'MEDIUM' ? '🟡' : '🔵';
  log(`  ${tag} [${severity}] ${page}: ${msg}`);
}

(async () => {
  const browser = await chromium.launch({ headless: true, args: ['--no-sandbox'] });
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, ignoreHTTPSErrors: true });
  const page = await ctx.newPage();

  // Collect console errors
  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') consoleErrors.push(msg.text());
  });

  // Collect failed requests
  const failedRequests = [];
  page.on('requestfailed', req => {
    failedRequests.push({ url: req.url(), error: req.failure()?.errorText });
  });

  // ═══════════════════════════════════════════════════════════════
  // PHASE 1: ADMIN LOGIN
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 1: ADMIN LOGIN ═══');
  await page.goto(`${BASE}/admin/login`);
  await page.screenshot({ path: 'screenshots/qa-01-login.png', fullPage: true });
  log('  ✓ Login page loaded');

  // Login
  await page.fill('input[name="username"]', 'admin');
  await page.fill('input[name="password"]', 'ViataLuxe2025!');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/admin/dashboard**', { timeout: 10000 });
  await page.screenshot({ path: 'screenshots/qa-02-dashboard.png', fullPage: true });
  log('  ✓ Logged in, dashboard loaded');

  // ═══════════════════════════════════════════════════════════════
  // PHASE 2: ADMIN PAGES - NAVIGATE ALL
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 2: ADMIN PAGES ═══');

  const adminPages = [
    { url: '/admin/pages', name: 'Pages List', file: 'qa-03-pages.png' },
    { url: '/admin/pages/edit?id=1', name: 'Edit Homepage', file: 'qa-04-edit-homepage.png' },
    { url: '/admin/pages/edit?id=2', name: 'Edit Contact', file: 'qa-05-edit-contact.png' },
    { url: '/admin/pages/edit?id=3', name: 'Edit Accommodation', file: 'qa-06-edit-accommodation.png' },
    { url: '/admin/pages/edit?id=4', name: 'Edit Safari', file: 'qa-07-edit-safari.png' },
    { url: '/admin/apartments', name: 'Apartments List', file: 'qa-08-apartments.png' },
    { url: '/admin/apartments/edit?id=1', name: 'Edit Apt 1', file: 'qa-09-edit-apt1.png' },
    { url: '/admin/apartments/edit?id=2', name: 'Edit Apt 2', file: 'qa-10-edit-apt2.png' },
    { url: '/admin/apartments/edit?id=3', name: 'Edit Apt 3', file: 'qa-11-edit-apt3.png' },
    { url: '/admin/apartments/edit?id=4', name: 'Edit Apt 4', file: 'qa-12-edit-apt4.png' },
    { url: '/admin/sections', name: 'Sections List', file: 'qa-13-sections.png' },
    { url: '/admin/gallery', name: 'Gallery Categories', file: 'qa-14-gallery-cat.png' },
    { url: '/admin/gallery/images', name: 'Gallery Images', file: 'qa-15-gallery-img.png' },
    { url: '/admin/testimonials', name: 'Testimonials', file: 'qa-16-testimonials.png' },
    { url: '/admin/navigation', name: 'Navigation', file: 'qa-17-navigation.png' },
    { url: '/admin/users', name: 'Users', file: 'qa-18-users.png' },
    { url: '/admin/settings', name: 'Settings', file: 'qa-19-settings.png' },
  ];

  for (const p of adminPages) {
    try {
      const resp = await page.goto(`${BASE}${p.url}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      const status = resp?.status();
      if (status && status >= 400) {
        issue('HIGH', p.name, `HTTP ${status} on ${p.url}`);
      }
      await page.waitForTimeout(500);
      await page.screenshot({ path: `screenshots/${p.file}`, fullPage: true });
      log(`  ✓ ${p.name} (${status})`);

      // Check for empty/error content
      const bodyText = await page.textContent('body');
      if (bodyText && bodyText.includes('Fatal error')) {
        issue('CRITICAL', p.name, 'PHP Fatal error on page');
      }
      if (bodyText && bodyText.includes('Warning:') && bodyText.includes('mysql')) {
        issue('HIGH', p.name, 'MySQL warning visible on page');
      }
    } catch (e) {
      issue('HIGH', p.name, `Navigation error: ${e.message}`);
    }
  }

  // ═══════════════════════════════════════════════════════════════
  // PHASE 3: EDIT APARTMENT - DETAILED TEST
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 3: EDIT APARTMENT (DETAILED) ═══');
  await page.goto(`${BASE}/admin/apartments/edit?id=1`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(500);

  // Check all form fields exist
  const formFields = await page.evaluate(() => {
    const inputs = document.querySelectorAll('input, textarea, select');
    return Array.from(inputs).map(el => ({
      tag: el.tagName,
      type: el.type || '',
      name: el.name || '',
      id: el.id || '',
      value: el.value || '',
      placeholder: el.placeholder || '',
      label: el.closest('label')?.textContent?.trim() || '',
      visible: el.offsetParent !== null
    })).filter(f => f.name && f.name !== 'csrf_token');
  });

  log(`  Form fields found: ${formFields.length}`);
  for (const f of formFields) {
    log(`    ${f.tag}[${f.type}] name="${f.name}" value="${f.value?.substring(0, 50)}" visible=${f.visible}`);
  }

  // Check for missing critical fields
  const criticalFields = ['name', 'price_per_night', 'max_guests', 'description', 'hero_image', 'page_id'];
  for (const field of criticalFields) {
    const found = formFields.find(f => f.name === field);
    if (!found) {
      issue('CRITICAL', 'Edit Apartment', `Missing critical field: ${field}`);
    } else if (!found.visible) {
      issue('MEDIUM', 'Edit Apartment', `Field '${field}' exists but is not visible`);
    }
  }

  // Check OG image field (Phase 1 addition)
  const ogField = formFields.find(f => f.name === 'og_image');
  if (!ogField) {
    issue('MEDIUM', 'Edit Apartment', 'OG image field missing (Phase 1)');
  }

  // Check if page_id dropdown has options
  const pageIdSelect = await page.$('select[name="page_id"]');
  if (pageIdSelect) {
    const options = await page.evaluate(el => {
      return Array.from(el.options).map(o => ({ value: o.value, text: o.textContent }));
    }, pageIdSelect);
    log(`  Page ID options: ${JSON.stringify(options)}`);
    if (options.length <= 1) {
      issue('MEDIUM', 'Edit Apartment', 'page_id dropdown has no options');
    }
  }

  // Check image browser button (actual class: .browse-btn[data-target])
  const browseBtn = await page.$('.browse-btn');
  if (!browseBtn) {
    issue('MEDIUM', 'Edit Apartment', 'Image browse button not found');
  }

  // Check form submission works (read-only test - don't actually save)
  const saveBtn = await page.$('button[type="submit"], .btn-save, input[type="submit"]');
  if (!saveBtn) {
    issue('HIGH', 'Edit Apartment', 'Save button not found');
  }

  // ═══════════════════════════════════════════════════════════════
  // PHASE 4: SECTION EDIT PAGES
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 4: SECTION EDIT PAGES ═══');

  // Get section IDs from the list
  await page.goto(`${BASE}/admin/sections`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(500);
  const sectionLinks = await page.evaluate(() => {
    const links = document.querySelectorAll('a[href*="sections/edit"]');
    return Array.from(links).map(a => ({
      href: a.href,
      text: a.textContent?.trim()
    }));
  });
  log(`  Found ${sectionLinks.length} section edit links`);

  // Test first 5 section edit pages
  for (let i = 0; i < Math.min(5, sectionLinks.length); i++) {
    try {
      const resp = await page.goto(sectionLinks[i].href, { waitUntil: 'domcontentloaded', timeout: 10000 });
      await page.waitForTimeout(500);
      await page.screenshot({ path: `screenshots/qa-section-${i}.png`, fullPage: true });
      log(`  ✓ Section edit: ${sectionLinks[i].text} (${resp?.status()})`);

      // Check form fields
      const secFields = await page.evaluate(() => {
        return Array.from(document.querySelectorAll('input, textarea, select')).map(el => ({
          name: el.name, type: el.type, visible: el.offsetParent !== null
        })).filter(f => f.name && f.name !== 'csrf_token');
      });
      log(`    Fields: ${secFields.length}`);

      // Check for orientation fields
      const orientFields = secFields.filter(f => f.name.includes('orientation') || f.name.includes('padding'));
      if (orientFields.length === 0) {
        log(`    ℹ No orientation/padding fields (expected for some types)`);
      }
    } catch (e) {
      issue('MEDIUM', `Section ${sectionLinks[i].text}`, `Error: ${e.message}`);
    }
  }

  // ═══════════════════════════════════════════════════════════════
  // PHASE 5: SETTINGS PAGE - DETAILED
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 5: SETTINGS PAGE ═══');
  await page.goto(`${BASE}/admin/settings`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(500);
  await page.screenshot({ path: 'screenshots/qa-20-settings.png', fullPage: true });

  const settingRows = await page.evaluate(() => {
    const rows = document.querySelectorAll('tr, .setting-row, [data-key]');
    return Array.from(rows).map(r => ({
      text: r.textContent?.trim()?.substring(0, 100),
      key: r.dataset?.key || ''
    }));
  });
  log(`  Settings rows: ${settingRows.length}`);

  // Check if settings are editable (inline edit or form)
  const editBtns = await page.$$('.edit-btn, .btn-edit, [data-edit], input[type="text"], textarea');
  log(`  Edit controls: ${editBtns.length}`);

  // ═══════════════════════════════════════════════════════════════
  // PHASE 6: FRONTEND PAGES
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 6: FRONTEND PAGES ═══');

  const frontendPages = [
    { url: '/', name: 'Homepage', file: 'qa-21-home.png' },
    { url: '/accommodation/', name: 'Accommodation', file: 'qa-22-accommodation.png' },
    { url: '/classic-apartment-bachelor/', name: 'Apt 1 Detail', file: 'qa-23-apt1.png' },
    { url: '/classic-apartment-2/', name: 'Apt 2 Detail', file: 'qa-24-apt2.png' },
    { url: '/comfort-apartment-3/', name: 'Apt 3 Detail', file: 'qa-25-apt3.png' },
    { url: '/deluxe-apartment-4/', name: 'Apt 4 Detail', file: 'qa-26-apt4.png' },
    { url: '/safari/', name: 'Safari', file: 'qa-27-safari.png' },
    { url: '/gallery/', name: 'Gallery', file: 'qa-28-gallery.png' },
    { url: '/contact/', name: 'Contact', file: 'qa-29-contact.png' },
    { url: '/nonexistent-page/', name: '404 Page', file: 'qa-30-404.png' },
  ];

  for (const p of frontendPages) {
    try {
      const resp = await page.goto(`${BASE}${p.url}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      const status = resp?.status();
      await page.waitForTimeout(1000); // Wait for animations
      await page.screenshot({ path: `screenshots/${p.file}`, fullPage: true });

      // Check for visible text content
      const bodyText = await page.evaluate(() => {
        const main = document.querySelector('main, #main-content, .main-content');
        return (main || document.body).textContent?.trim()?.substring(0, 500);
      });

      if (bodyText.length < 50) {
        issue('HIGH', p.name, `Very little text content (${bodyText.length} chars) — may be blank`);
      }

      // Check for broken images
      const brokenImages = await page.evaluate(() => {
        return Array.from(document.querySelectorAll('img')).filter(img => !img.complete || img.naturalWidth === 0).map(img => img.src);
      });
      if (brokenImages.length > 0) {
        issue('HIGH', p.name, `${brokenImages.length} broken image(s): ${brokenImages[0]?.substring(0, 80)}`);
      }

      // Check for console errors specific to this page
      const pageErrors = consoleErrors.filter(e => !e.includes('favicon'));
      if (pageErrors.length > 0) {
        issue('MEDIUM', p.name, `Console errors: ${pageErrors[0]?.substring(0, 100)}`);
      }

      // Check for ?? or ? characters (encoding issues)
      const fullText = await page.evaluate(() => document.body.textContent);
      const mojibake = fullText.match(/\?\?/g);
      if (mojibake && mojibake.length > 0) {
        issue('HIGH', p.name, `Found ${mojibake.length} double-question-mark sequences (possible encoding issue)`);
      }

      log(`  ✓ ${p.name} (${status}) — content: ${bodyText.length} chars, images: ${brokenImages.length} broken`);
    } catch (e) {
      issue('HIGH', p.name, `Navigation error: ${e.message}`);
    }
  }

  // ═══════════════════════════════════════════════════════════════
  // PHASE 7: MOBILE RESPONSIVENESS
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 7: MOBILE RESPONSIVENESS ═══');
  await page.setViewportSize({ width: 375, height: 812 });
  await page.waitForTimeout(300);

  for (const p of frontendPages.slice(0, 5)) {
    try {
      await page.goto(`${BASE}${p.url}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      await page.waitForTimeout(1000);
      await page.screenshot({ path: `screenshots/qa-mobile-${p.file.replace('qa-', '')}`, fullPage: true });

      // Check for horizontal overflow
      const hasOverflow = await page.evaluate(() => {
        return document.documentElement.scrollWidth > document.documentElement.clientWidth;
      });
      if (hasOverflow) {
        issue('MEDIUM', `${p.name} (mobile)`, 'Horizontal overflow detected');
      }

      // Check if hamburger menu exists
      const hamburger = await page.$('.nav__toggle, .hamburger, .menu-toggle, [data-nav-toggle]');
      if (!hamburger && p.name === 'Homepage') {
        log(`    ℹ No hamburger menu found (may use different selector)`);
      }

      log(`  ✓ ${p.name} (mobile) — overflow: ${hasOverflow}`);
    } catch (e) {
      issue('MEDIUM', `${p.name} (mobile)`, `Error: ${e.message}`);
    }
  }

  // Reset viewport
  await page.setViewportSize({ width: 1440, height: 900 });

  // ═══════════════════════════════════════════════════════════════
  // PHASE 8: API ENDPOINTS
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 8: API / DYNAMIC ENDPOINTS ═══');

  const apiEndpoints = [
    { url: '/sitemap.php', name: 'Sitemap XML', check: 'xml' },
    { url: '/api/contact.php', name: 'Contact API (GET)', check: 'json' },
  ];

  for (const ep of apiEndpoints) {
    try {
      const resp = await page.goto(`${BASE}${ep.url}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      const status = resp?.status();
      const body = await page.textContent('body');

      if (status >= 400) {
        issue('HIGH', ep.name, `HTTP ${status}`);
      }
      if (body.includes('Fatal error')) {
        issue('CRITICAL', ep.name, 'PHP Fatal error');
      }
      if (ep.check === 'xml' && !body.includes('<?xml')) {
        issue('HIGH', ep.name, 'Not valid XML');
      }
      log(`  ✓ ${ep.name} (${status}) — ${body.length} bytes`);
    } catch (e) {
      issue('HIGH', ep.name, `Error: ${e.message}`);
    }
  }

  // ═══════════════════════════════════════════════════════════════
  // PHASE 9: NAVIGATION LINKS
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 9: FRONTEND NAVIGATION LINKS ═══');
  await page.goto(`${BASE}/`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(500);

  const navLinks = await page.evaluate(() => {
    const links = document.querySelectorAll('nav a, .nav a, header a');
    return Array.from(links).map(a => ({
      href: a.href,
      text: a.textContent?.trim(),
      visible: a.offsetParent !== null
    })).filter(l => l.href && l.text);
  });
  log(`  Navigation links found: ${navLinks.length}`);
  for (const link of navLinks) {
    log(`    ${link.visible ? '✓' : '○'} "${link.text}" → ${link.href}`);
  }

  // Check for broken nav links
  for (const link of navLinks.filter(l => l.visible && l.href.includes(BASE))) {
    try {
      const resp = await page.goto(link.href, { waitUntil: 'domcontentloaded', timeout: 5000 });
      if (resp?.status() >= 400) {
        issue('HIGH', 'Navigation', `Broken link: "${link.text}" → ${resp.status()}`);
      }
    } catch (e) {
      issue('MEDIUM', 'Navigation', `Link error: "${link.text}" → ${e.message}`);
    }
  }

  // ═══════════════════════════════════════════════════════════════
  // PHASE 10: GALLERY PAGE DETAIL
  // ═══════════════════════════════════════════════════════════════
  log('\n═══ PHASE 10: GALLERY PAGE ═══');
  await page.goto(`${BASE}/gallery/`, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(2000);
  await page.screenshot({ path: 'screenshots/qa-31-gallery-full.png', fullPage: true });

  // Check gallery images
  const galleryImages = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('.masonry__item img, .gallery img, .grid img')).map(img => ({
      src: img.src?.substring(0, 80),
      loaded: img.complete && img.naturalWidth > 0,
      alt: img.alt
    }));
  });
  log(`  Gallery images: ${galleryImages.length}`);
  const brokenGallery = galleryImages.filter(i => !i.loaded);
  if (brokenGallery.length > 0) {
    issue('HIGH', 'Gallery', `${brokenGallery.length} broken gallery images`);
  }

  // Check filter buttons
  const filterBtns = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('.filter-btn, [data-cat]')).map(b => ({
      text: b.textContent?.trim(),
      cat: b.dataset?.cat
    }));
  });
  log(`  Filter buttons: ${JSON.stringify(filterBtns)}`);

  // ═══════════════════════════════════════════════════════════════
  // SUMMARY
  // ═══════════════════════════════════════════════════════════════
  log('\n═══════════════════════════════════════════════════════════════');
  log('                    QA TEST SUMMARY');
  log('═══════════════════════════════════════════════════════════════');

  const critical = issues.filter(i => i.severity === 'CRITICAL');
  const high = issues.filter(i => i.severity === 'HIGH');
  const medium = issues.filter(i => i.severity === 'MEDIUM');
  const low = issues.filter(i => i.severity === 'LOW');

  log(`\n  🔴 CRITICAL: ${critical.length}`);
  log(`  🟠 HIGH:     ${high.length}`);
  log(`  🟡 MEDIUM:   ${medium.length}`);
  log(`  🔵 LOW:      ${low.length}`);
  log(`  TOTAL:       ${issues.length}`);

  if (issues.length > 0) {
    log('\n── ALL ISSUES ──');
    issues.forEach((iss, i) => {
      const tag = iss.severity === 'CRITICAL' ? '🔴' : iss.severity === 'HIGH' ? '🟠' : iss.severity === 'MEDIUM' ? '🟡' : '🔵';
      log(`  ${i + 1}. ${tag} [${iss.severity}] ${iss.page}: ${iss.msg}`);
    });
  }

  // Write issues to file
  const report = {
    timestamp: new Date().toISOString(),
    totalIssues: issues.length,
    critical: critical.length,
    high: high.length,
    medium: medium.length,
    low: low.length,
    issues,
    consoleErrors: consoleErrors.slice(0, 20),
    failedRequests: failedRequests.slice(0, 20)
  };
  fs.writeFileSync('screenshots/qa-report.json', JSON.stringify(report, null, 2));
  log(`\n  Report saved to screenshots/qa-report.json`);
  log(`  Screenshots saved to screenshots/qa-*.png`);

  await browser.close();
})();
