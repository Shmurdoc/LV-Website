import { test, expect } from '@playwright/test';

test('admin login with seed creds succeeds', async ({ page }) => {
  await page.goto('/admin/login');
  await page.getByLabel('Username or Email').fill('admin');
  await page.getByLabel('Password').fill('ViataLuxe2025!');
  await page.getByRole('button', { name: /sign in/i }).click();
  await page.waitForURL('**/admin/dashboard', { timeout: 10000 });
  console.log(`after login url=${page.url()}`);
  const body = await page.content();
  const isDashboard = /Dashboard|admin/i.test(body) || page.url().includes('/admin/dashboard');
  console.log(`isDashboard=${isDashboard}`);
  await page.screenshot({ path: 'tests/audit-admin-after-login.png', fullPage: true });
  expect(isDashboard).toBeTruthy();
});

test('contact POST creates row (no auth required)', async ({ page }) => {
  await page.goto('/contact');
  const token = await page.getAttribute('input[name="csrf_token"]', 'value');
  console.log(`csrf token=${token?.slice(0, 8)}`);

  const r = await page.request.post('/api/contact', {
    form: {
      csrf_token: token || '',
      name: 'Playwright Tester',
      email: 'pw@test.com',
      message: 'Brutal audit message from Playwright — 10+ chars'
    }
  });
  console.log(`contact POST status=${r.status()} body=${(await r.text()).slice(0, 500)}`);
  expect(r.status()).toBe(200);
});
