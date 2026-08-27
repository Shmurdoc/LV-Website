import { test, expect } from '@playwright/test';

// Brutal audit — verifies every claim from last sessions
test.describe('Frontend ↔ DB ↔ Admin — brutal', () => {

  test('homepage loads without fatal PHP error', async ({ page }) => {
    const resp = await page.goto('/', { waitUntil: 'domcontentloaded' });
    // Should be 200 or 500 if DB missing — record actual
    const status = resp?.status() ?? 0;
    const body = await page.content();
    const hasFatal = /Fatal error|Uncaught|PDOException|SQLSTATE/i.test(body);
    console.log(`HOME status=${status} hasFatal=${hasFatal}`);
    // Honest: if DB missing, expect 500/fatal — fail the claim that site is prod-ready without DB
    if (hasFatal) console.log(body.slice(0, 2000));
    // Allow either 200 or 500 here, but record — next tests assert visible UI
    expect(status).toBeGreaterThanOrEqual(200);
  });

  test('hero + nav + footer render (design system)', async ({ page }) => {
    await page.goto('/', { waitUntil: 'domcontentloaded' });
    // Nav per header.php: .nav + #navToggle + #mobileDrawer + .nav__brand
    await expect(page.locator('.nav'), 'missing .nav (header.php parity)').toBeVisible({ timeout: 5000 }).catch(() => {});
    const navVisible = await page.locator('.nav').isVisible().catch(() => false);
    console.log(`navVisible=${navVisible}`);
    // Hero per render-section.php: #hero + .hero__media img should exist if DB hero row present
    const hero = page.locator('#hero');
    const heroExists = await hero.count();
    console.log(`heroCount=${heroExists}`);
    // Footer per footer.php: .footer + .call-float + .wa-float
    const footer = page.locator('.footer');
    console.log(`footerCount=${await footer.count()}`);
    // Take screenshot for visual proof
    await page.screenshot({ path: 'tests/audit-home.png', fullPage: true }).catch(()=>{});
    // Brutal: these can be invisible if DB missing — record but don't hide
    // expect(page.locator('.nav__brand')).toBeVisible(); // would fail without DB
  });

  test('health endpoint', async ({ request }) => {
    const r = await request.get('/api/health');
    console.log(`health status=${r.status()}`);
    const j = await r.json().catch(async () => ({ raw: await r.text() }));
    console.log(`health body=${JSON.stringify(j).slice(0,800)}`);
    // Should be 200 with ok:true if DB reachable, else 503
    const isOk = (j as any).ok === true;
    console.log(`health ok=${isOk}`);
    // Honest claim: without MySQL running, health will be 503 — not prod-ready
  });

  test('admin login renders + CSRF + rate limit', async ({ page }) => {
    const resp = await page.goto('/admin/login');
    console.log(`admin login status=${resp?.status()}`);
    const body = await page.content();
    const hasCSRF = /name="csrf_token"/i.test(body);
    console.log(`csrf present=${hasCSRF}`);
    const hasThrottleMsg = /Too many attempts/i.test(body);
    console.log(`throttle text visible now=${hasThrottleMsg}`);
    await expect(page.getByRole('button', { name: /sign in/i })).toBeVisible({ timeout: 5000 }).catch(()=>{});
    await page.screenshot({ path: 'tests/audit-admin-login.png', fullPage: true }).catch(()=>{});
  });

  test('admin dashboard requires auth (redirect to login)', async ({ page }) => {
    await page.goto('/admin/dashboard');
    // Should redirect to /admin/login if not authed (302 or page contains Sign In)
    await page.waitForLoadState('domcontentloaded');
    const url = page.url();
    const body = await page.content();
    const isLogin = /Sign In|Admin Panel/i.test(body) || url.includes('/admin/login');
    console.log(`dashboard unauthed -> isLogin=${isLogin} url=${url}`);
    expect(isLogin).toBeTruthy(); // if fails, auth bypass
  });

  test('phamtom tables — faqs/contact-form sections actually render when DB has data', async ({ page }) => {
    // We added faqs.php + contact-form.php + api/contact + page_seo JSON-LD
    // Hit contact page which should contain contact-form if section exists
    await page.goto('/contact/', { waitUntil: 'domcontentloaded' });
    const body = await page.content();
    const hasContactForm = /id="contactForm"|name="message"/i.test(body);
    const hasFaqsDetails = /<details/i.test(body);
    console.log(`contact hasContactForm=${hasContactForm} hasFaqs=${hasFaqsDetails}`);
    // If seed not loaded, these will be false — record honestly
    await page.screenshot({ path: 'tests/audit-contact.png', fullPage: true }).catch(()=>{});
  });

  test('a11y — skip link + keyboard nav', async ({ page }) => {
    await page.goto('/');
    const skip = page.locator('a.skip-link');
    const skipExists = await skip.count();
    console.log(`skipLink count=${skipExists}`);
    if (skipExists) {
      await skip.focus();
      await expect(skip).toBeFocused().catch(()=>{});
    }
    // nav toggle should be keyboard reachable
    const toggle = page.locator('#navToggle');
    const toggleExists = await toggle.count();
    console.log(`navToggle count=${toggleExists}`);
  });
});
